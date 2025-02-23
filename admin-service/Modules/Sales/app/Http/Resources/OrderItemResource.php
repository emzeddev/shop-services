<?php

namespace Modules\SaLes\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Http\Resources\ProductResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'order_id'   => $this->order_id,
            'additional' => (object) $this->resource->additional ?? [],
            'product'    => new ProductResource($this->product),
        ];
    }
}
