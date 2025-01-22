<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Attribute\Repositories\AttributeRepository;
use Modules\Category\Repositories\CategoryRepository;
use Modules\Core\Repositories\ChannelRepository;
use Modules\Category\DataGrids\CategoryDataGrid;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Http\Requests\MassDestroyRequest;
use Modules\Category\Http\Requests\MassUpdateRequest;
use Modules\Category\Transformers\CategoryTreeResource;

use Illuminate\Http\JsonResponse;


class CategoryController extends Controller
{

    public function __construct(
        protected ChannelRepository $channelRepository,
        protected CategoryRepository $categoryRepository,
        protected AttributeRepository $attributeRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return new JsonResponse([
            "status" => 200,
            "data" => datagrid(CategoryDataGrid::class)->process()
        ] , JsonResponse::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $categories = $this->categoryRepository->getCategoryTree();

        $attributes = $this->attributeRepository->findWhere(['is_filterable' => 1]);

        return new JsonResponse([
            "categories" => $categories,
            "attributes" => $attributes
        ] , JsonResponse::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $categoryRequest)
    {

        $category = $this->categoryRepository->create($categoryRequest->only([
            'locale',
            'name',
            'parent_id',
            'description',
            'slug',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'status',
            'position',
            'display_mode',
            'attributes',
            'logo_path',
            'banner_path',
        ]));

        return new JsonResponse([
            "message" => trans("category::messages.category_created"),
            "category" => $category
        ] , JsonResponse::HTTP_CREATED);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $category = $this->categoryRepository->findOrFail($id);

        $categories = $this->categoryRepository->getCategoryTreeWithoutDescendant($id);

        $attributes = $this->attributeRepository->findWhere(['is_filterable' => 1]);

        return new JsonResponse([
            "category"   => $category,
            "categories" => $categories,
            "attributes" => $attributes
        ] , JsonResponse::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $categoryRequest, int $id)
    {

        $category = $this->categoryRepository->update($categoryRequest->only(
            'locale',
            'parent_id',
            'logo_path',
            'banner_path',
            'position',
            'display_mode',
            'status',
            'attributes',
            $categoryRequest->input('locale')
        ), $id);


        return new JsonResponse([
            "message" => trans("category::messages.category_updated"),
            "category" => $category
        ] , JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $category = $this->categoryRepository->findOrFail($id);

        if (! $this->isCategoryDeletable($category)) {
            return new JsonResponse([
                'message' => trans('category::messages.delete-category-root'),
            ], 400);
        }

        try {

            $category->delete($id);


            return new JsonResponse([
                'message' => trans('category::messages.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('category::messages.delete-failed'),
            ], 500);
        }
    }


    /**
     * Remove the specified resources from database.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $suppressFlash = true;

        $categoryIds = $massDestroyRequest->input('indices');

        foreach ($categoryIds as $categoryId) {
            $category = $this->categoryRepository->find($categoryId);

            if (isset($category)) {
                if (! $this->isCategoryDeletable($category)) {
                    $suppressFlash = false;

                    return new JsonResponse(['message' => trans('category::messages.delete-category-root')], 400);
                } else {
                    try {
                        $suppressFlash = true;


                        $this->categoryRepository->delete($categoryId);

                    } catch (\Exception $e) {
                        return new JsonResponse([
                            'message' => trans('category::messages.delete-failed'),
                        ], 500);
                    }
                }
            }
        }

        if (
            count($categoryIds) != 1
            || $suppressFlash == true
        ) {
            return new JsonResponse([
                'message' => trans('admin::app.catalog.categories.delete-success'),
            ]);
        }

        return redirect()->route('admin.catalog.categories.index');
    }



     /**
     * Mass update Category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest)
    {
        try {
            $categoryIds = $massUpdateRequest->input('indices');

            foreach ($categoryIds as $categoryId) {

                $category = $this->categoryRepository->find($categoryId);

                $category->status = $massUpdateRequest->input('value');

                $category->save();

            }

            return new JsonResponse([
                'message' => trans('category::messages.update-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Check whether the current category is deletable or not.
     *
     * This method will fetch all root category ids from the channel. If `id` is present,
     * then it is not deletable.
     *
     * @param  \Modules\Category\Contracts\Category  $category
     * @return bool
     */
    private function isCategoryDeletable($category)
    {
        if ($category->id === 1) {
            return false;
        }

        return ! $this->channelRepository->pluck('root_category_id')->contains($category->id);
    }


    /**
     * Get all categories in tree format.
     */
    public function tree(): JsonResource
    {
        $categories = $this->categoryRepository->getVisibleCategoryTree(core()->getRequestedChannel()->root_category_id);

        return CategoryTreeResource::collection($categories);
    }

    /**
     * Get all the searched categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $categories = $this->categoryRepository->getAll([
            'name'   => request()->input('query'),
            'locale' => app()->getLocale(),
        ]);

        return new JsonResponse([
            'categories' => $categories,
        ]);

    }
}
