<?php

namespace Denmasyarikin\Sales;

use App\Manager\Facades\Package;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Relations\Relation;

class SalesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Relation::morphMap([
            'product' => 'Modules\Product\Product',
            'product_process' => 'Modules\Product\ProductProcess',
        ]);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        Package::register('sales', __DIR__, 'Denmasyarikin\Sales');

        Validator::extend('voucher', function ($attribute, $value, $parameters) {
            return false;
        }, 'Invalid voucher code');
    }
}
