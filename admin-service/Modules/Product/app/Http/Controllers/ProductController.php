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
        ] , JsonResponse::HTTP_CREATED);
    }

}
