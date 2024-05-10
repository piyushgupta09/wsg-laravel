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
        if ($this->slug == 'featured') {
            $collectionProducts = [];
        } else {
            $products = $this->products->sortBy('created_at')->take(10);
            $collectionProducts = $products->map(function ($product) {
                $collectionProduct = CollectionProduct::where('collection_id', $this->id)->where('product_id', $product->id)->first();
                $selectedProductOption = $collectionProduct->productOption->slug;
               
                $productMrp = $product->mrp;
                if ($productMrp == null || $productMrp == 0) {
                    $productMrp = $product->productRanges->first()->mrp ?? 0;
                }
                $productRate = $product->rate;
                if ($productRate == null || $productRate == 0) {
                    $productRate = $product->productRanges->first()->rate ?? 0;
                }
    
                return [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'code' => $product->code,
                    'mrp' => $productMrp,
                    'rate' => $productRate,
                    'moq' => $product->moq,
                    'image' => $product->getImage(Collection::MEDIA_CONVERSION_PREVIEW),
                    'sizes' => $product->productRanges->pluck('slug'),
                    'options' => ProductOptionResource::collection($product->productOptions->sortBy('id')),
                    'selected' => $selectedProductOption,
                ];
            });
        }

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
