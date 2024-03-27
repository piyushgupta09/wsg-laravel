<?php

namespace Fpaipl\Shopy\Http\Resources;

use Illuminate\Http\Request;
use Fpaipl\Prody\Models\ProductOption;
use Illuminate\Http\Resources\Json\JsonResource;
use Fpaipl\Shopy\Http\Resources\CartItemResource;
use Fpaipl\Prody\Http\Resources\ProductRangeResource;
use Fpaipl\Prody\Http\Resources\ProductOptionResource;

class CartProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cartImage = null;
        $cartItemFirstProductOptionId = $this->cartItems->first()->productOption->id ?? null;
        if ($cartItemFirstProductOptionId) {
            $cartImage = ProductOption::find($cartItemFirstProductOptionId)->getImage('s100');
        }

        return [

            // CartProduct
            // 'id' => $this->id, CartProductId is not needed in the frontend
            'draft' => $this->draft, 
            'items' => CartItemResource::collection($this->whenLoaded('cartItems')),

            // Product
            'name' => $this->product->name,
            'slug' => $this->product->slug,
            'code' => $this->product->code,
            'image' => $cartImage,
            // 'image' => $this->product->productOptions->first()->getImage(),
            'brand' => $this->product->brand->name,
            'category' => $this->product->category->name,
            'options' => $this->whenLoaded('product', function() {
                return ProductOptionResource::collection($this->product->productOptions);
            }),
            'ranges' => $this->whenLoaded('product', function() {
                return ProductRangeResource::collection($this->product->productRanges);
            }),
            'order_type' => $this->product->getUserCartProductOrderType(),
            'cartProductItems' => $this->product->getUserCartProducts(),
        ];
    }
}
