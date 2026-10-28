<?php

namespace App\Providers;


use App\Payment\PaymentInterface;
use App\Payment\PaymentProcess;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Payment', function () {
            return new PaymentProcess(app(PaymentInterface::class));
        });
        $this->app->bind(PaymentInterface::class, 'App\\Payment\\Providers\\' . ucwords(Str::camel(config('payment.provider'))));
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
