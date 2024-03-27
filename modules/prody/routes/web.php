<?php

use Illuminate\Support\Facades\Route;
use Fpaipl\Prody\Http\Controllers\TaxController;
use Fpaipl\Prody\Http\Controllers\SyncController;
use Fpaipl\Prody\Http\Controllers\BrandController;
use Fpaipl\Prody\Http\Controllers\ProductController;
use Fpaipl\Prody\Http\Controllers\CategoryController;
use Fpaipl\Prody\Http\Controllers\WsgBrandController;
use Fpaipl\Prody\Http\Controllers\CollectionController;

Route::middleware(['web','auth'])->group(function () {
    
    // Route::get('sync', [SyncController::class, 'all'])->name('sync.all');

    Route::resource('taxes', TaxController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('collections', CollectionController::class);
    

    Route::get('wsg-brands', [WsgBrandController::class, 'index'])->name('wsg-brands.index');
    Route::get('wsg-brands/{wsg_brand}', [WsgBrandController::class, 'show'])->name('wsg-brands.show');

});
