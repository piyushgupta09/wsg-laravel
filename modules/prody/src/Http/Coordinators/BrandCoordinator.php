<?php

namespace Fpaipl\Prody\Http\Coordinators;

use Fpaipl\Prody\Models\Brand;
use Fpaipl\Prody\Models\Product;
use Fpaipl\Prody\Http\Resources\BrandResource;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Prody\Http\Resources\ProductResource;

class BrandCoordinator extends Coordinator
{
    public function index()
    {
        // Cache::forget('brands');
        // $brands = Cache::remember('brands', 24 * 60 * 60, function () {
        //     return Brand::all();
        // });
        $brands = Brand::all();
        return BrandResource::collection($brands);
    }

    public function show(Brand $brand)
    {
        $products = $brand->products->where('active', 1)->sortBy('name');
        return ProductResource::collection($products);
    }
}
