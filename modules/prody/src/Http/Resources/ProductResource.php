<?php

namespace Fpaipl\Prody\Http\Resources;

use Fpaipl\Prody\Models\Collection;
use Illuminate\Http\Request;
use Fpaipl\Prody\Http\Resources\CollectionResource;
use Fpaipl\Prody\Http\Resources\ProductBrandResource;
use Fpaipl\Prody\Http\Resources\ProductRangeResource;
use Fpaipl\Prody\Http\Resources\ProductOptionResource;
use Fpaipl\Prody\Http\Resources\ProductCategoryResource;
use Fpaipl\Shopy\Http\Resources\ReviewResource;
use Fpaipl\Prody\Http\Resources\ProductAttributeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $inCart = $this->isInUserCart();

        $recommended = [];
        $collections = Collection::active()->with('products')->get()->groupBy('type');
        if ($collections->isNotEmpty()) {
            if ($collections->has('recommended')) {
                $recommended = CollectionResource::collection($collections->get('recommended'));
            }
        }

        $fpr = $this->productRanges->first();

        // $productMeasurements = ProductMeasurementResource::collection($this->productMeasurements);

        // Step 1: Prepare Header Data
        $sizes = $this->productMeasurements->pluck('size')->unique();

        // Step 2: Prepare Body Data
        // Group measurements by 'name', then for each name, organize values by size.
        $measurementsBySize = $this->productMeasurements->groupBy('name')->map(function ($measurements) use ($sizes) {
            // For each name, create an array of values for each size
            $valuesBySize = [];
            foreach ($sizes as $size) {
                // Find the measurement for the current size and name, if exists
                $measurement = $measurements->firstWhere('size', $size);
                $valuesBySize[$size] = $measurement ? $measurement->value : null;
            }
            return $valuesBySize;
        });

        $calculateStarts = function ($rating) {
            $fullStars = floor($rating);
            $halfStars = ceil($rating - $fullStars);
            $emptyStars = 5 - $fullStars - $halfStars;
            return [
                'fullStars' => $fullStars,
                'halfStars' => $halfStars,
                'emptyStars' => $emptyStars,
            ];
        };

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'hsn_code' => $this->tax->hsncode,
            'gst_rate' => $this->tax->gstrate,
            'mrp' => $fpr->mrp,
            'discount' => number_format(($fpr->mrp - $fpr->rate) / $fpr->mrp * 100, 0),
            'rate' => $fpr->rate,
            'details' => $this->details,
            'moq' => $this->moq,
            'stars' => $calculateStarts(4.5),
            'image' => $this->productOptions?->first()?->getImage('s400'),
            'images' => $this->productOptions?->map(fn ($productOption) => $productOption->getImage('s1200'))->flatten()->toArray(),
            'brand' => new ProductBrandResource($this->brand),
            'category' => new ProductCategoryResource($this->category),
            'options' => ProductOptionResource::collection($this->productOptions->sortBy('id')),
            'ranges' => ProductRangeResource::collection($this->productRanges->sortBy('id')),
            'attributes' => ProductAttributeResource::collection($this->productAttributes->sortBy('id')),
            'measurements' => [
                'sizes' => $sizes,
                'measurementsBySize' => $measurementsBySize,
            ],
            'sharelink' => config('app.client_url') . '/product/' . $this->slug,
            'reviews' => new ReviewResource(''),
            'offers' => [
                (object) [
                    'icon' => 'bi bi-truck',
                    'title' => 'Pick-up & Drop Shipping facility',
                ],
                (object) [
                    'icon' => 'bi bi-shield-check',
                    'title' => 'Secure & Easy Payment System',
                ],
                (object) [
                    'icon' => 'bi bi-headset',
                    'title' => '10am to 6pm Support',
                ],
                (object) [
                    'icon' => 'bi bi-credit-card-2-front',
                    'title' => 'Defective Product Return Available',
                ],
            ],
            'recommended' => $recommended,
            'order_type' => $inCart ? $this->getUserCartProductOrderType() : null,
            'inMyCart' => $inCart,
            'cartProductItems' => $inCart ? $this->getUserCartProducts() : [],
        ];
    }
}
