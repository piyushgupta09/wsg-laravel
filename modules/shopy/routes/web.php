<?php

use Illuminate\Support\Facades\Route;
use Fpaipl\Shopy\Http\Controllers\OrderController;
use Fpaipl\Shopy\Http\Controllers\CouponController;
use Fpaipl\Shopy\Http\Controllers\PaymentController;
use Fpaipl\Shopy\Http\Controllers\DeliveryController;
use Fpaipl\Shopy\Http\Controllers\NewOrderController;
use Fpaipl\Shopy\Http\Controllers\ProcessingOrderController;

Route::middleware(['web','auth'])->group(function () {

    Route::resource('orders', OrderController::class);
    Route::get('new-orders', [ NewOrderController::class, 'index' ])->name('new-orders.index');
    Route::get('processing-orders', [ ProcessingOrderController::class, 'index' ])->name('processing-orders.index');
    Route::resource('deliveries', DeliveryController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('coupons', CouponController::class);
});
