<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Modules\Product\Repositories\ProductRepository;

class GroupedController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected ProductRepository $productRepository) {}

    /**
     * Returns the compare items of the customer.
     */
    public function options(int $id): JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);

        $options = $product->grouped_products()->orderBy('sort_order')->get();

        $products = [];

        foreach ($options as $option) {
            if (! $option->associated_product->getTypeInstance()->isSaleable()) {
                continue;
            }

            $products[] = [
                'id'              => $option->associated_product->id,
                'name'            => $option->associated_product->name,
                'qty'             => $option->qty,
                'price'           => $price = $option->associated_product->getTypeInstance()->getFinalPrice(),
                'formatted_price' => core()->formatPrice($price),
            ];
        }

        return new JsonResponse([
            'data' => $products,
        ] , JsonResponse::HTTP_OK);
    }
}
