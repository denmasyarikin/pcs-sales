<?php

namespace Denmasyarikin\Sales;

use App\Manager\Facades\Package;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class SalesServiceProvider extends ServiceProvider
{
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
