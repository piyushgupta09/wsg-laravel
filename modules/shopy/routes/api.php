<?php

use Illuminate\Support\Facades\Route;
use Fpaipl\Shopy\Http\Coordinators\BankCoordinator;
use Fpaipl\Shopy\Http\Coordinators\CartCoordinator;
use Fpaipl\Shopy\Http\Coordinators\OrderCoordinator;
use Fpaipl\Shopy\Http\Coordinators\CouponCoordinator;
use Fpaipl\Shopy\Http\Coordinators\PaymentCoordinator;
use Fpaipl\Shopy\Http\Coordinators\CheckoutCoordinator;
use Fpaipl\Shopy\Http\Coordinators\DeliveryCoordinator;
use Fpaipl\Shopy\Http\Coordinators\PickupAddressCoordinator;

Route::middleware(['api', 'auth:sanctum'])->name('api.')->prefix('api')->group(function () {
    Route::resource('carts', CartCoordinator::class);
    
    Route::put('add-to-cart/{cart}', [CartCoordinator::class, 'addToCart']);
    Route::put('save-for-later/{cart}', [CartCoordinator::class, 'saveForLater']);
    Route::put('move-to-cart/{cart}', [CartCoordinator::class, 'moveToCart']);
    Route::put('remove-from-cart/{cart}', [CartCoordinator::class, 'removeFromCart']);

    Route::get('coupons', [CouponCoordinator::class, 'index']);
    Route::post('coupons/apply', [CouponCoordinator::class, 'couponApply']);
    Route::post('coupons/remove', [CouponCoordinator::class, 'couponRemove']);

    Route::get('checkout', [CheckoutCoordinator::class, 'index']);
    Route::post('checkout/start', [CheckoutCoordinator::class, 'start']);
    Route::post('checkout/billing', [CheckoutCoordinator::class, 'billing']);
    Route::post('checkout/delivery', [CheckoutCoordinator::class, 'delivery']);
    Route::post('checkout/payment', [CheckoutCoordinator::class, 'payment']);

    Route::get('pickup-addresses', [PickupAddressCoordinator::class, 'index']);
    Route::get('bank-details', [BankCoordinator::class, 'index']);
    Route::post('create-order', [OrderCoordinator::class, 'store']);
    Route::post('payments', [PaymentCoordinator::class, 'store']);
    
    Route::get('orders', [OrderCoordinator::class, 'index']);
    Route::get('active-order', [OrderCoordinator::class, 'activeOrder']);
    Route::get('orders/{order}', [OrderCoordinator::class, 'show']);
    Route::get('pending-orders', [OrderCoordinator::class, 'pending']);
    Route::get('completed-orders', [OrderCoordinator::class, 'completed']);
    Route::post('update-delivery', [DeliveryCoordinator::class, 'updateDelivery']);
});
