<?php

namespace App\Providers;

use App\Models\CashFlow;
use App\Models\Product;
use App\Observers\CashFlowObserver;
use App\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
    public function boot()
    {
        Product::observe(ProductObserver::class);
        CashFlow::observe(CashFlowObserver::class);
    }
}
