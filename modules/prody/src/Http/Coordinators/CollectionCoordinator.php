<?php

namespace Fpaipl\Prody\Http\Coordinators;

use Fpaipl\Prody\Models\Collection;
use Illuminate\Support\Facades\Cache;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Prody\Http\Resources\CollectionResource;

class CollectionCoordinator extends Coordinator
{
    // public function index()
    // {
    //     $featured = array();
    //     $ranged = array();
    //     $recommended = array();
    //     Cache::forget('collections');

    //     if (config('panel.api.cache.enabled')) {
    //         $collections = Cache::remember('collections', config('panel.api.cache.duration'), function () {
    //             return Collection::active()->with('products')->get()->groupBy('type');
    //         });
    //     } else {
    //         Cache::forget('collections');
    //         $collections = Collection::active()->with('products')->get()->groupBy('type');
    //     }

    //     if ($collections->isNotEmpty()) {
    //         if ($collections->has('featured')) {
    //             $featured = CollectionResource::collection(
    //                 $collections->get('featured')->filter(function($collection) {
    //                     return $collection->products->isNotEmpty();
    //                 })
    //             );
    //         }
    //         if ($collections->has('ranged')) {
    //             $ranged = CollectionResource::collection(
    //                 $collections->get('ranged')->filter(function($collection) {
    //                     return $collection->products->isNotEmpty();
    //                 })
    //             );
    //         }
    //         if ($collections->has('recommended')) {
    //             $recommended = CollectionResource::collection(
    //                 $collections->get('recommended')->filter(function($collection) {
    //                     return $collection->products->isNotEmpty();
    //                 })
    //             );
    //         }
    //     }

    //     return response()->json([
    //         'data' => [
    //             'featured' => empty($featured) ? [] : $featured,
    //             'ranged' => empty($ranged) ? [] : $ranged,
    //             'recommended' => empty($recommended) ? [] : $recommended,
    //         ]
    //     ]);
    // }

    public function index()
    {
        // Assuming CollectionResource is properly imported at the top of your controller file.

        // Forgetting the cache only if you need to ensure fresh data on each call,
        // otherwise, you might want to remove this line to leverage caching effectively.
        Cache::forget('collections');

        $collections = Cache::remember('collections', config('panel.api.cache.duration'), function () {
            return Collection::active()->with('products')->get()->groupBy('type');
        });

        $featured = $collections->has('featured') ? CollectionResource::collection(
            $collections->get('featured')->filter(function ($collection) {
                return $collection->products->isNotEmpty();
            })
        ) : [];

        $ranged = $collections->has('ranged') ? CollectionResource::collection(
            $collections->get('ranged')->filter(function ($collection) {
                return $collection->products->isNotEmpty();
            })
        ) : [];

        $recommended = $collections->has('recommended') ? CollectionResource::collection(
            $collections->get('recommended')->filter(function ($collection) {
                return $collection->products->isNotEmpty();
            })
        ) : [];

        return response()->json([
            'data' => [
                'featured' => $featured,
                'ranged' => $ranged,
                'recommended' => $recommended,
            ]
        ]);
    }

    public function show(Collection $collection)
    {
        $collection->load('products');
        return new CollectionResource($collection);
    }
}
