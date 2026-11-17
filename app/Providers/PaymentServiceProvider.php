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
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('general_settings')) {
                $paymentSettings = \App\Domain\AppBase\Models\GeneralSetting::query()
                    ->select('moyasar_key', 'moyasar_secret', 'moyasar_url')
                    ->find(1);

                if ($paymentSettings) {
                    if (!empty($paymentSettings->moyasar_key)) {
                        config(['services.moyasar.key' => $paymentSettings->moyasar_key]);
                        config(['moyasar.publishable_key' => $paymentSettings->moyasar_key]);
                    }
                    if (!empty($paymentSettings->moyasar_secret)) {
                        config(['services.moyasar.secret' => $paymentSettings->moyasar_secret]);
                        config(['moyasar.key' => $paymentSettings->moyasar_secret]);
                    }
                    if (!empty($paymentSettings->moyasar_url)) {
                        config(['services.moyasar.url' => $paymentSettings->moyasar_url]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently fall back to config/env
        }
    }
}
