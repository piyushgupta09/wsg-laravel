<?php

namespace Fpaipl\Prody\Http\Coordinators;

use Illuminate\Http\Request;
use Fpaipl\Prody\Models\Brand;
use Fpaipl\Prody\Models\Product;
use Fpaipl\Prody\Models\Category;
use Fpaipl\Prody\Models\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Fpaipl\Prody\Models\ProductOption;
use Fpaipl\Prody\Models\CollectionProduct;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Prody\Http\Resources\CollectionResource;
use Fpaipl\Prody\Http\Resources\ProductOptionResource;

class CollectionCoordinator extends Coordinator
{
    public function index()
    {
        // Check if caching is enabled in the configuration
        if (!config('panel.api.cache.enabled')) {
            // Clear the cache if caching is disabled
            Cache::forget('collections');
        }
    
        // Retrieves or creates cached collections based on the configuration setting
        $collections = Cache::remember('collections', config('panel.api.cache.duration'), function () {
            return Collection::active()->with(['products' => function ($query) {
                $query->latest();
            }])->get()->groupBy('type');
        });
    
        // Process each collection type to extract and limit the data as required
        $featured = $this->processCollectionType($collections, 'featured', false);
        $ranged = $this->processCollectionType($collections, 'ranged');
        $custom = $this->processCollectionType($collections, 'custom');
        $recommended = $this->processCollectionType($collections, 'recommended');
    
        // Return the structured response with data
        return response()->json([
            'data' => [
                'featured' => $featured,
                'ranged' => $ranged,
                'custom' => $custom,
                'recommended' => $recommended,
            ]
        ]);
    }

    protected function processCollectionType($collections, $type, $loadProducts = true)
    {
        return $collections->has($type) ? CollectionResource::collection(
            $collections->get($type)->map(function ($collection) use ($type, $loadProducts) {
                if ($loadProducts) {
                    $collection->products = $collection->products->sortByDesc('created_at')->take(8);
                } else {
                    // If products should not be loaded, empty the products collection
                    $collection->setRelation('products', collect([]));
                }
                return $collection;
            })->filter(function ($collection) use ($loadProducts) {
                return $loadProducts ? $collection->products->isNotEmpty() : true;
            })
        ) : [];
    }

    public function show(Collection $collection)
    {
        $collection->load('products');
        return new CollectionResource($collection);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param String $type , default, category, brand, collection, tags
     * @param String|null $data , slug of the category, brand, collection or tag
     * @return void
     */
    public function products(Request $request, String $type, String $data = null)
    {
        // validate params
        $validated = $request->validate([
            'page' => 'required|integer',
        ]);

        // Define the number of products to display per page
        $perPage = 20;

        switch ($type) {
            case 'category':
                // Check if the category with the specified slug exists
                if (!Category::where('slug', $data)->exists()) {
                    return response()->json(['page' => false, 'error' => 'Category not found'], 404);
                }
                $collection = Category::where('slug', $data)
                                        ->with(['products' => function($query) use ($perPage, $validated) {
                                            $query->orderByDesc('created_at')
                                                ->forPage($validated['page'], $perPage);
                                        }])
                                        ->first();
                break;
            
            case 'brand':
                // check if the brand with the specified slug exists
                if (!Brand::where('slug', $data)->exists()) {
                    return response()->json(['page' => false, 'error' => 'Brand not found'], 404);
                }
                $collection = Brand::where('slug', $data)
                                    ->with(['products' => function($query) use ($perPage, $validated) {
                                        $query->orderByDesc('created_at')
                                            ->forPage($validated['page'], $perPage);
                                    }])
                                    ->first();
                break;

            case 'collection':
                // Check if the collection with the specified slug exists
                if (!Collection::where('slug', $data)->exists()) {
                    return response()->json(['page' => false, 'error' => 'Collection not found'], 404);
                }
                $collection = Collection::where('slug', $data)
                                        ->with(['products' => function($query) use ($perPage, $validated) {
                                            $query->orderByDesc('created_at')
                                                ->forPage($validated['page'], $perPage);
                                        }])
                                        ->first();
                break;

            case 'tags':
                $collection = Product::where('tags', 'like', '%'.$data.'%')
                    ->orderByDesc('created_at')
                    ->forPage($validated['page'], $perPage)->get();

            default:
                // Attempt to retrieve the recommended collection with pagination applied at the database level
                $collection = Collection::where('type', 'recommended')
                                        ->with(['products' => function($query) use ($validated, $perPage) {
                                            $query->orderByDesc('created_at')
                                                ->forPage($validated['page'], $perPage);
                                        }])
                                        ->first();
                break;
        }


        // Check if the collection exists and has products
        if (!$collection) {
            return response()->json(['page' => false, 'error' => 'No products found for the specified page'], 200);
        }

        // Check if the number of products is less than the perPage, meaning this is the last page
        if ($type !== 'tags' && $collection->products->count() < $perPage) {
            $nextPage = false;
        } elseif ($type === 'tags' && $collection->count() < $perPage) {
            $nextPage = false;
        } else {
            $nextPage = $validated['page'] + 1;
        }

        switch ($type) {
            case 'tags': 
                $data = $collection->map(function ($product) {
                    $selectedProductOption = $product->productOptions->first()->slug;
            
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
                        'image' => $product->getImage(ProductOption::MEDIA_CONVERSION_PREVIEW),
                        'sizes' => $product->productRanges->pluck('slug'),
                        'options' => ProductOptionResource::collection($product->productOptions->sortBy('id')),
                        'selected' => $selectedProductOption,
                    ];
                });
                break;


            case 'category':
                $data = $collection->products->map(function ($product) {
                    $selectedProductOption = $product->productOptions->first()->slug;
            
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
                        'image' => $product->getImage(ProductOption::MEDIA_CONVERSION_PREVIEW),
                        'sizes' => $product->productRanges->pluck('slug'),
                        'options' => ProductOptionResource::collection($product->productOptions->sortBy('id')),
                        'selected' => $selectedProductOption,
                    ];
                });
                break;

            case 'brand':
                $data = $collection->products->map(function ($product) {
                    $selectedProductOption = $product->productOptions->first()->slug;
            
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
                        'image' => $product->getImage(ProductOption::MEDIA_CONVERSION_PREVIEW),
                        'sizes' => $product->productRanges->pluck('slug'),
                        'options' => ProductOptionResource::collection($product->productOptions->sortBy('id')),
                        'selected' => $selectedProductOption,
                    ];
                });
                break;

            case 'collection':
                $data = $collection->products->map(function ($product) use ($collection){
                    $collectionProduct = CollectionProduct::where('collection_id', $collection->id)->where('product_id', $product->id)->first();
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
                break;
                
            default:
                $data = (new CollectionResource($collection))['products'];
                break;
        }

        // Return the collection resource with next page indication
        return response()->json([
            'page' => $nextPage,
            'data' => [
                'products' => $data,
            ],
        ]);
    }
}
