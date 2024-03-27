<?php

use Illuminate\Support\Facades\Route;
use Fpaipl\Prody\Http\Coordinators\SyncCoordinator;
use Fpaipl\Prody\Http\Coordinators\BrandCoordinator;
use Fpaipl\Prody\Http\Coordinators\MyShopCoordinator;
use Fpaipl\Prody\Http\Coordinators\SearchCoordinator;
use Fpaipl\Prody\Http\Coordinators\ProductCoordinator;
use Fpaipl\Prody\Http\Coordinators\CategoryCoordinator;
use Fpaipl\Prody\Http\Coordinators\FavouriteCoordinator;
use Fpaipl\Prody\Http\Coordinators\CollectionCoordinator;

Route::middleware(['api', 'auth:sanctum'])->name('api.')->prefix('api')->group(function () {

    Route::get('support-details', function () {
        return json_encode([
            'message' => 'success',
            'data' => config('settings.support')
        ]);
    });

    Route::get('about-page', function () {
        return json_encode([
            'message' => 'success',
            'data' => config('settings.about')
        ]);
    });

    Route::get('terms-page', function () {
        return json_encode([
            'message' => 'success',
            'data' => config('settings.terms')
        ]);
    });

    Route::get('privacy-page', function () {
        return json_encode([
            'message' => 'success',
            'data' => config('settings.privacy')
        ]);
    });

    Route::get('faqs-page', function () {
        return json_encode([
            'message' => 'success',
            'data' => config('settings.faq')
        ]);
    });

    Route::get('search', [SearchCoordinator::class, 'search']);
    Route::get('topSearchTags', [SearchCoordinator::class, 'topSearchTags']);
    Route::get('myshop', [MyShopCoordinator::class, 'index']);
    Route::get('collections', [CollectionCoordinator::class, 'index']);
    Route::get('collections/{collection}', [CollectionCoordinator::class, 'show']);
    Route::get('categories', [CategoryCoordinator::class, 'index']);
    Route::get('categories/{category}', [CategoryCoordinator::class, 'show']);
    Route::get('categories/all/{category}',[CategoryCoordinator::class, 'viewall']);
    Route::get('brands', [BrandCoordinator::class, 'index']);
    Route::get('brands/{brand}', [BrandCoordinator::class, 'show']);
    Route::get('products', [ProductCoordinator::class, 'index']);
    Route::get('products/{product}', [ProductCoordinator::class, 'show']);
    Route::get('favourites', [FavouriteCoordinator::class, 'index']);
    Route::post('favourites', [FavouriteCoordinator::class, 'favourites']);
    Route::post('favourite/{product}', [FavouriteCoordinator::class, 'toggle']);
    Route::get('surprise', [ProductCoordinator::class, 'surprise']);
});


// Api's for WsgBrand
Route::middleware(['api'])->prefix('api')->group(function () {    
    Route::prefix('sync')->group(function () {
        Route::post('taxes/{wsgbrand}', [SyncCoordinator::class, 'taxes']);
        Route::post('brands/{wsgbrand}', [SyncCoordinator::class, 'brands']);
        Route::post('categories/{wsgbrand}', [SyncCoordinator::class, 'categories']);
        Route::post('collections/{wsgbrand}', [SyncCoordinator::class, 'collections']);
    });
});
