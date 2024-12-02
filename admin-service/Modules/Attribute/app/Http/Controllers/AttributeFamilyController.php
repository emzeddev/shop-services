<?php

namespace Modules\Attribute\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Attribute\Repositories\AttributeFamilyRepository;
use Modules\Attribute\Repositories\AttributeRepository;
use Illuminate\Http\JsonResponse;
use Modules\Attribute\DataGrids\AttributeFamilyDataGrid;
use Modules\Attribute\Http\Requests\AttributeFamilyStoreRequest;

class AttributeFamilyController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected AttributeRepository $attributeRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new JsonResponse([
            "status" => 200,
            "data" => datagrid(AttributeFamilyDataGrid::class)->process()
        ] , JsonResponse::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributeFamily = $this->attributeFamilyRepository->with(['attribute_groups.custom_attributes'])->findOneByField('code', 'default');

        $customAttributes = $this->attributeRepository->all(['id', 'code', 'admin_name', 'type', 'is_user_defined']);

        return new JsonResponse([
            "attributeFamily" => $attributeFamily,
            "customAttributes" => $customAttributes
        ] , JsonResponse::HTTP_OK);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeFamilyStoreRequest $request)
    {
        $this->attributeFamilyRepository->create([
            'attribute_groups' => request('attribute_groups'),
            'code'             => request('code'),
            'name'             => request('name'),
        ]);

        return new JsonResponse([
            "message" => trans("attribute::messages.attribute_family_created")
        ] , JsonResponse::HTTP_CREATED);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('attribute::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $attributeFamily = $this->attributeFamilyRepository->with(['attribute_groups.custom_attributes'])->findOrFail($id, ['*']);

        $customAttributes = $this->attributeRepository->all(['id', 'code', 'admin_name', 'type', 'is_user_defined']);

        return new JsonResponse([
            "attributeFamily" => $attributeFamily,
            "customAttributes" => $customAttributes
        ] , JsonResponse::HTTP_OK);
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
