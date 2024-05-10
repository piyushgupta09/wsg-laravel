<?php

namespace Fpaipl\Prody\Http\Coordinators;

use Fpaipl\Prody\Models\Product;
use Fpaipl\Prody\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Prody\Http\Resources\ProductResource;
use Fpaipl\Prody\Http\Resources\CategoryResource;
use Fpaipl\Prody\Http\Resources\ProductCardResource;

class CategoryCoordinator extends Coordinator
{
    public $categoryWithChilds = array();
    
    public function index()
    {
        // Try to find the 'womens' category; if not found, use a default category with id 1
        $women = Category::where('slug', 'womens')->first() ?? Category::findOrFail(1);

        // Get categories that are active, displayable, and children of the found category
        $categories = Category::active()->display()->where('parent_id', $women->id)->get();

        // Return the categories as a resource collection
        return CategoryResource::collection($categories->values()->all());
    }

    public function show(Category $category)
    {
        $category_id = $category->id;
        // Log::info('category_id: '.$category_id);
        Cache::forget('products'.$category_id);
        $products = Cache::remember('products'.$category_id, 24 * 60 * 60, function () use($category_id) {
            return Product::where('category_id', $category_id)->get();
        });
        return ProductCardResource::collection($products->take(10));
    }


    public function viewall(Category $category){
        $this->getChilds($category);
        Cache::forget('products'.$category->id);
        $products = Cache::remember('products'.$category->id, 24 * 60 * 60, function () {
            return Product::with('category')
                ->with('taxation')
                ->with('colors')
                ->wherein('category_id', $this->categoryWithChilds)
                ->whereStatus(Product::STATUS[1])
                ->get();
        });
        return ProductResource::collection($products);
    }

    private function getChilds(Category $category){
        array_push($this->categoryWithChilds, $category->id);
        if($category->hasChild()){
            foreach($category->child as $child){
                $this->getChilds($child);
            }
        }
    }
}
