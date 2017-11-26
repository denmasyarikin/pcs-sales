<?php

namespace Denmasyarikin\Sales;

use App\Manager\Facades\Package;
use Illuminate\Support\ServiceProvider;

class SalesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        Package::register('sales', __DIR__, 'Denmasyarikin\Sales');
    }
}
