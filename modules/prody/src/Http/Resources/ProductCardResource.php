<?php

namespace Fpaipl\Prody\Http\Resources;

use Illuminate\Http\Request;
use Fpaipl\Prody\Models\ProductOption;
use Illuminate\Http\Resources\Json\JsonResource;
use Fpaipl\Prody\Http\Resources\ProductOptionResource;

class ProductCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $images = $this->productOptions->map(function ($option) {
            return $option->getImage(ProductOption::MEDIA_CONVERSION_PREVIEW);
        })->filter();

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'mrp' => 500,
            'rate' => 500,
            'moq' => $this->moq,
            'image' => $images?->first(),
            'images' => $images,
            'sizes' => $this->productRanges->pluck('name'),
            'options' => ProductOptionResource::collection($this->productOptions->sortBy('id')),
        ];
    }
}
