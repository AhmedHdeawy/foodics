<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OrderService\OrderService;
use App\Services\StockService\StockService;
use App\Services\OrderService\OrderServiceContract;
use App\Services\StockService\StockServiceContract;

class AppServiceProvider extends ServiceProvider
{

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        OrderServiceContract::class => OrderService::class,
        StockServiceContract::class => StockService::class
    ];

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
        //
    }
}
