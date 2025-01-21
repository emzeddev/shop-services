<?php

namespace Modules\Attribute\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Modules\Attribute\DataGrids\AttributeDataGrid;
use Modules\Attribute\Http\Controllers\MainController;
use Modules\Attribute\Repositories\AttributeRepository;
// use Modules\Product\Repositories\ProductRepository;
use Modules\Attribute\Http\Requests\MassDestroyRequest;
use Modules\Core\Rules\Code;
use Modules\Attribute\Http\Requests\AttributeStoreRequest;

class AttributeController extends MainController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        // protected ProductRepository $productRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return new JsonResponse([
            "status" => 200,
            "data" => datagrid(AttributeDataGrid::class)->process()
        ] , JsonResponse::HTTP_OK, [], JSON_PRETTY_PRINT);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeStoreRequest $request)
    {

        $requestData = request()->all();

        $requestData['default_value'] ??= null;


        $this->attributeRepository->create($requestData);

        // Event::dispatch('catalog.attribute.create.after', $attribute);


        return new JsonResponse([
            "message" => trans("attribute::messages.attribute_created")
        ] , JsonResponse::HTTP_CREATED);
    }


    /**
     * Get attribute options associated with attribute.
     *
     * @return \Illuminate\View\View
     */
    public function getAttributeOptions(int $id)
    {
        $attribute = $this->attributeRepository->findOrFail($id);


        return new JsonResponse([
            "data" => $attribute->options()->orderBy('sort_order')->get()
        ] , JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'code'          => ['required', 'unique:attributes,code,'.$id, new Code],
            'admin_name'    => 'required',
            'type'          => 'required',
            'default_value' => 'integer',
        ]);

        $requestData = request()->all();

        if (! $requestData['default_value']) {
            $requestData['default_value'] = null;
        }

        $this->attributeRepository->update($requestData, $id);


        return new JsonResponse([
            "message" => trans("attribute::messages.attribute_updated")
        ] , JsonResponse::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $attribute = $this->attributeRepository->findOrFail($id);

        if (! $attribute->is_user_defined) {
            return response()->json([
                'message' => trans('admin::app.catalog.attributes.user-define-error'),
            ], 400);
        }

        try {
            Event::dispatch('catalog.attribute.delete.before', $id);

            $this->attributeRepository->delete($id);

            Event::dispatch('catalog.attribute.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.catalog.attributes.delete-success'),
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('admin::app.catalog.attributes.delete-failed'),
        ], 500);
    }

    /**
     * Remove the specified resources from database.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {
            $attribute = $this->attributeRepository->find($index);

            if (! $attribute->is_user_defined) {
                return response()->json([
                    'message' => trans('admin::app.catalog.attributes.delete-failed'),
                ], 422);
            }
        }

        foreach ($indices as $index) {
            Event::dispatch('catalog.attribute.delete.before', $index);

            $this->attributeRepository->delete($index);

            Event::dispatch('catalog.attribute.delete.after', $index);
        }

        return new JsonResponse([
            'message' => trans('admin::app.catalog.attributes.index.datagrid.mass-delete-success'),
        ]);
    }

    // /**
    //  * Get super attributes of product.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function productSuperAttributes(int $id)
    // {
    //     $product = $this->productRepository->findOrFail($id);

    //     $superAttributes = $this->productRepository->getSuperAttributes($product);

    //     return response()->json([
    //         'data'  => $superAttributes,
    //     ]);
    // }
}

