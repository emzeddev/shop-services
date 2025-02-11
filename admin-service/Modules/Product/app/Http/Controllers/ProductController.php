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

}
