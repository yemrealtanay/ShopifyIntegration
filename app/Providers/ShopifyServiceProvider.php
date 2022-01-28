<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Shopify\Context;
use Shopify\Auth\FileSessionStorage;
use Shopify\Clients\Rest;

class ShopifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Rest::class, function ($app){
            Context::initialize(
                config('services.shopify.app_key'),
                config('services.shopify.app_password'),
                config('services.shopify.app_scopes'),
                config('services.shopify.app_host'),
                $app->make(FileSessionStorage::class),
                config('services.shopify.api_version'),
                false,
                true
            );

            return new Rest(config('services.shopify.store_domain'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
