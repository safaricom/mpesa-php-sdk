<?php

namespace Safaricom\Mpesa;

use Illuminate\Support\ServiceProvider;

/**
 * Class MpesaServiceProvider
 * @package Safaricom\Mpesa
 */
class MpesaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Mpesa::class, function () {
            return new Mpesa();
        });

    }
}
