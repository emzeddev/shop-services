<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\Attribute\Repositories\AttributeRepository;
use Modules\Category\Repositories\CategoryRepository;
use Modules\Category\DataGrids\CategoryDataGrid;
use Illuminate\Http\JsonResponse;
use Modules\Category\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{

    public function __construct(
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
    public function store(Request $categoryRequest)
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


        // session()->flash('success', trans('admin::app.catalog.categories.create-success'));

        // return redirect()->route('admin.catalog.categories.index');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('category::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('category::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
