<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\ShippingCodeRepositoryInterface::class,
            \App\Repositories\Eloquent\ShippingCodeRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\ContainerRepositoryInterface::class,
            \App\Repositories\Eloquent\ContainerRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
