<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PaypageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('paypage', function ($app) {
            return new \App\Payment\ClickPay\paypage();
        });
        $this->mergeConfigFrom(
            // __DIR__ . '/config/config.php', 'clickpay'
            __DIR__ . '/../Payment/ClickPay/config/config.php',
            'clickpay'
        );

        $this->app->make(\App\Payment\ClickPay\Controllers\ClickpayLaravelListenerApi::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            // __DIR__ . '/config/config.php' => config_path('clickpay.php'),
            __DIR__ . '/../Payment/ClickPay/config/config.php' => config_path('clickpay.php'),
        ], 'clickpay');

        // include __DIR__.'/../routes/routes.php';
    }
}
