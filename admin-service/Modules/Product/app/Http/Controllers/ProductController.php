<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Modules\Attribute\DataGrids\AttributeDataGrid;

class ProductController extends Controller
{
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
}
