<?php

namespace Fpaipl\Shopy\Http\Resources;

use Illuminate\Http\Request;
use Fpaipl\Prody\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'skus' => $this->skus,
            'quantity' => $this->quantity,
            'suborder_id' => $this->suborder_id,
            'total' => $this->total,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'items' => OrderItemResource::collection($this->orderItems),
            'product' => new ProductResource($this->product),
            'order_tax' => $this->orderTax,
       ];
    }
}
