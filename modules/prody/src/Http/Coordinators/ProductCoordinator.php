<?php

namespace Fpaipl\Prody\Http\Coordinators;

use Fpaipl\Prody\Models\Product;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Prody\Http\Resources\ProductResource;
use Fpaipl\Prody\Http\Resources\ProductApiResource;

class ProductCoordinator extends Coordinator
{
    public function index()
    {
        $products = Product::with('productOptions', 'productRanges')->active()->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => ProductApiResource::collection($products)
        ]);
    }

    public function show(Product $product)
    {
        // $id = $product->id;
        // Cache::forget('products'.$id);
        
        // $product = Cache::remember('products'.$id, 24 * 60 * 60, function () use($id) {
        //     return Product::with('category', 'productOptions', 'productRanges')->active()->find($id);
        // });

        $product->load('brand', 'category', 'productOptions', 'productRanges', 'productAttributes', 'productMeasurements');  

        return response()->json([
            'status' => 'success',
            'data' => new ProductResource($product)
        ]);
    }

    public function surprise()
    {
        $product = Product::active()->inRandomOrder()->first();
        return new ProductResource($product);
    }
}
