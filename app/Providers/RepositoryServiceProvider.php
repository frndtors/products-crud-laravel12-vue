<?php

namespace App\Providers;

use App\Contracts\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind ProductRepositoryInterface to ProductRepository
        $this->app->singleton(ProductRepositoryInterface::class, function ($app) {
            return new ProductRepository($app->make(\App\Models\Product::class));
        });

        // Also bind the concrete class for backward compatibility
        $this->app->singleton(ProductRepository::class, function ($app) {
            return $app->make(ProductRepositoryInterface::class);
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
