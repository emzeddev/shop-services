<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Modules\Attribute\DataGrids\AttributeDataGrid;
use Illuminate\Support\Facades\Storage;
use Modules\Product\DataGrids\ProductDataGrid;
use Modules\Attribute\Repositories\AttributeFamilyRepository;
use Modules\Product\Repositories\ProductAttributeValueRepository;
use Modules\Product\Repositories\ProductDownloadableLinkRepository;
use Modules\Product\Repositories\ProductDownloadableSampleRepository;
use Modules\Product\Repositories\ProductInventoryRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Http\Requests\CreateProductRequest;
use Modules\Attribute\Http\Resources\AttributeResource;
use Modules\Product\Helpers\ProductType;
use Modules\Product\Http\Requests\ProductForm;
use Modules\Product\Http\Requests\InventoryRequest;
use Modules\Product\Http\Requests\MassDestroyRequest;
use Modules\Product\Http\Requests\MassUpdateRequest;
use Modules\Product\Http\Resources\ProductResource;

class ProductController extends Controller
{

    /*
    * Using const variable for status
    */
    const ACTIVE_STATUS = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository,
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        protected ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected ProductRepository $productRepository,
    ) {}

   /**
     * To be manually invoked when data is seeded into products.
     *
     * @return \Illuminate\Http\Response
     */
    public function sync()
    {
        return new JsonResponse([
            "message" => "Syncing products data...",
        ] , JsonResponse::HTTP_OK);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return new JsonResponse([
            "status" => 200,
            "data" => datagrid(ProductDataGrid::class)->process()
        ] , JsonResponse::HTTP_OK, [], JSON_PRETTY_PRINT);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
    */
    public function create()
    {
        $families = $this->attributeFamilyRepository->all();

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamilyRepository->find($familyId);
        }


        return new JsonResponse([
            "status" => 200,
            "data" => [
                "families" => $families,
                "configurableFamily" => $configurableFamily,
            ]
        ] , JsonResponse::HTTP_OK, [], JSON_PRETTY_PRINT);

    }



    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateProductRequest $request)
    {
        if (
            ProductType::hasVariants(request()->input('type'))
            && ! request()->has('super_attributes')
        ) {
            $configurableFamily = $this->attributeFamilyRepository
                ->find(request()->input('attribute_family_id'));

            return new JsonResponse([
                'data' => [
                    'attributes' => AttributeResource::collection($configurableFamily->configurable_attributes),
                ],
            ]);
        }


        $product = $this->productRepository->create(request()->only([
            'type',
            'attribute_family_id',
            'sku',
            'super_attributes',
            'family',
        ]));


        return new JsonResponse([
            'data' => [
                'message' => trans('product::app.products.create-success'),
                'redirect_to_product' => $product->id, // redirect to product edit page
            ],
        ] , JsonResponse::HTTP_CREATED);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $product = $this->productRepository->findOrFail($id);

        return new JsonResponse([
            'data' => [
                'product' => $product,
            ],
        ] , JsonResponse::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
    */
    public function update(ProductForm $request, int $id)
    {
        $product = $this->productRepository->update(request()->all(), $id);

        return new JsonResponse([
            'data' => [
                'message' => trans('product::app.products.update-success'),
                'product' => $product
            ],
        ] , JsonResponse::HTTP_OK);
    }


     /**
     * Update inventories.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateInventories(InventoryRequest $inventoryRequest, int $id)
    {
        $product = $this->productRepository->findOrFail($id);


        $this->productInventoryRepository->saveInventories(request()->all(), $product);


        return new JsonResponse([
            'data' => [
                'message' => __('product::app.products.saved-inventory-message'),
                'product' => $this->productInventoryRepository->where('product_id', $product->id)->sum('qty')
            ],
        ] , JsonResponse::HTTP_OK);

    }



    /**
     * Uploads downloadable file.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadLink(int $id)
    {
        return new JsonResponse([
            "data" => $this->productDownloadableLinkRepository->upload(request()->all(), $id)
        ] , JsonResponse::HTTP_OK);
    }



    /**
     * Copy a given Product.
     *
     * @return \Illuminate\Http\Response
     */
    public function copy(int $id)
    {
        try {

            $product = $this->productRepository->copy($id);

        } catch (\Exception $e) {
            return new JsonResponse([
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ] , JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        session()->flash('success', trans('product::app.products.product-copied'));

        return new JsonResponse([
            'data' => [
                'message' => trans('product::app.products.product-copied'),
                'redirect_to_product' => $product->id // redirect to edit page product
            ],
        ] , JsonResponse::HTTP_OK);

    }


    /**
     * Uploads downloadable sample file.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadSample(int $id)
    {
        return new JsonResponse([
            "data" => $this->productDownloadableSampleRepository->upload(request()->all(), $id)
        ] , JsonResponse::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->productRepository->delete($id);

            return new JsonResponse([
                'message' => trans('product::app.products.delete-success'),
            ] , JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            report($e);
        }

        return new JsonResponse([
            'message' => trans('product::app.products.delete-failed'),
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }


    /**
     * Mass delete the products.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $productIds = $massDestroyRequest->input('indices');

        try {
            foreach ($productIds as $productId) {
                $product = $this->productRepository->find($productId);

                if (isset($product)) {
                    $this->productRepository->delete($productId);
                }
            }

            return new JsonResponse([
                'message' => trans('admin::app.catalog.products.index.datagrid.mass-delete-success'),
            ] , JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.catalog.products.index.datagrid.mass-delete-success'),
            ] , JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Mass update the products.
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $productIds = $massUpdateRequest->input('indices');

        foreach ($productIds as $productId) {
            $product = $this->productRepository->update([
                'status'  => $massUpdateRequest->input('value'),
            ], $productId, ['status']);

        }

        return new JsonResponse([
            'message' => trans('product::app.products.index.datagrid.mass-update-success'),
        ], JsonResponse::HTTP_OK);
    }


    
    /**
     * Result of search product.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $results = [];

        $searchEngine = 'database';

        if (
            core()->getConfigData('catalog.products.search.engine') == 'elastic'
            && core()->getConfigData('catalog.products.search.admin_mode') == 'elastic'
        ) {
            $searchEngine = 'elastic';

            $indexNames = core()->getAllChannels()->map(function ($channel) {
                return 'products_'.$channel->code.'_'.app()->getLocale().'_index';
            })->toArray();
        }

        $products = $this->productRepository
            ->setSearchEngine($searchEngine)
            ->getAll([
                'index' => $indexNames ?? null,
                'name'  => request('query'),
                'sort'  => 'created_at',
                'order' => 'desc',
            ]);

        return new JsonResponse([
            'data' => ProductResource::collection($products),
        ], JsonResponse::HTTP_OK);

    }



     /**
     * Download image or file.
     *
     * @param  int  $productId
     * @param  int  $attributeId
     * @return \Illuminate\Http\Response
     */
    public function download($productId, $attributeId)
    {
        $productAttribute = $this->productAttributeValueRepository->findOneWhere([
            'product_id'   => $productId,
            'attribute_id' => $attributeId,
        ]);

        return Storage::download($productAttribute['text_value']);
    }

}
