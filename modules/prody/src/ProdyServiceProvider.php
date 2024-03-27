<?php

namespace Fpaipl\Prody;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Fpaipl\Prody\Http\Livewire\WsgBrandCard;
use Fpaipl\Prody\Http\Livewire\ProductRanges;
use Fpaipl\Prody\Http\Livewire\ProductStatus;
use Fpaipl\Prody\Http\Livewire\ProductDetails;
use Fpaipl\Prody\Http\Livewire\ProductOptions;
use Fpaipl\Prody\Http\Livewire\CollectionProducts;

class ProdyServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/resources/views', 'prody');
        $this->loadViewComponentsAs('prody', []);

        Livewire::component('wsg-brand-card', WsgBrandCard::class);
        Livewire::component('product-options', ProductOptions::class);
        Livewire::component('product-ranges', ProductRanges::class);
        Livewire::component('product-status', ProductStatus::class);
        Livewire::component('product-details', ProductDetails::class);
        Livewire::component('collection-products', CollectionProducts::class);
    }
}
