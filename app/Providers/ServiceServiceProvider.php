<?php

namespace App\Providers;

use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ProductService::class, function ($app) {
            return new ProductService($app->make(ProductRepository::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
