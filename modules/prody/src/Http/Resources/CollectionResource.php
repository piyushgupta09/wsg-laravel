<?php

namespace Fpaipl\Prody\Http\Resources;

use Illuminate\Http\Request;
use Fpaipl\Prody\Models\Collection;
use Fpaipl\Prody\Models\CollectionProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $collectionProducts = $this->products->map(function ($product) {
            $collectionProduct = CollectionProduct::where('collection_id', $this->id)->where('product_id', $product->id)->first();
            $selectedProductOption = $collectionProduct->productOption->slug;
            return [
                'name' => $product->name,
                'slug' => $product->slug,
                'code' => $product->code,
                'mrp' => 500,
                'rate' => 500,
                'moq' => $product->moq,
                'image' => $product->getImage(Collection::MEDIA_CONVERSION_PREVIEW),
                'sizes' => $product->productRanges->pluck('slug'),
                'options' => ProductOptionResource::collection($product->productOptions->sortBy('id')),
                'selected' => $selectedProductOption,
            ];
        });

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'shade' => $this->shade,
            'image' => $this->getImage(Collection::MEDIA_CONVERSION_PREVIEW),
            'info' => $this->info,
            'products' => $collectionProducts,
        ];
    }
}
