<?php

namespace Fpaipl\Shopy\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ColorSize;

class CartProductDeleteResource extends JsonResource
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $colorSize = ColorSize::withTrashed()->findOrFail($this->data['colorSize']);
        $product = $colorSize->colorWithTrashed->productWithTrashed;
        return [
            'category'=> [
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ],
            'name' => $product->name,
            'slug' => $product->slug,
            'code' => $product->code,
            'base_price' => $product->base_price,
            'selling_price' => $product->selling_price,
            'quantity' => $this->data['quantity'],
            'color' => $colorSize->colorWithTrashed->name,
            'size' => $colorSize->sizeWithTrashed->name
    ];
    }
}
