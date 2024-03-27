<?php

namespace Fpaipl\Shopy;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Fpaipl\Shopy\Http\Livewire\OrderPayment;
use Fpaipl\Shopy\Http\Livewire\OrderDelivery;
use Fpaipl\Shopy\Http\Livewire\OrderPayments;
use Fpaipl\Shopy\Http\Livewire\OrderProducts;
use Fpaipl\Shopy\Http\Livewire\DiscountCoupon;
use Fpaipl\Shopy\Http\Livewire\OrderDeliveries;

class ShopyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'shopy');
        $this->loadViewComponentsAs('shopy', []);
        Livewire::component('order-products', OrderProducts::class);
        Livewire::component('order-payment', OrderPayment::class);
        Livewire::component('order-payments', OrderPayments::class);
        Livewire::component('order-delivery', OrderDelivery::class);
        Livewire::component('order-deliveries', OrderDeliveries::class);
        Livewire::component('discount-coupon', DiscountCoupon::class);
    }
}
