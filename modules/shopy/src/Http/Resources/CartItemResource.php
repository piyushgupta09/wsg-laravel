<?php

namespace Fpaipl\Shopy\Http\Resources;

use Illuminate\Http\Request;
use Fpaipl\Prody\Models\ProductRange;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->quantity * $this->productRange->rate,
            'quantity' => $this->quantity,
            'option_id' => $this->product_option_id,
            'range_id' => $this->product_range_id,
            'option' => $this->productOption->getImage('s100'),
            'range' => ProductRange::SHORT[$this->productRange->slug],
        ];
    }
}