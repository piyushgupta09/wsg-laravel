<?php

namespace Fpaipl\Shopy\Http\Resources;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'mrp' => floatval($this->mrp),
            'rate' => floatval($this->rate),
            'price' => floatval($this->price),
            'discount' => $this->discount,
            'quantity' => $this->quantity,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'total' => $this->total,
            'product_option_id' => $this->product_option_id,
            'product_range_id' => $this->product_range_id,
       ];
    }
}
