<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\StockMovement;
use App\Models\Team;
use App\Observers\ProductObserver;
use App\Observers\ProductOrderObserver;
use App\Observers\StockObserver;
use App\Observers\TeamObserver;
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
        Team::observe(TeamObserver::class);

        
        ProductOrder::observe(ProductOrderObserver::class);
        StockMovement::observe(StockObserver::class);
    }
}
