<?php

namespace App\Support\Traits\Alerts;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait WithLocalizedAlerts
{
    protected function localizedAlert(string $type = 'success', string $message = '', array $options = []): void
    {
        $this->alert($type, $message, $this->localizedAlertOptions($options));
    }

    protected function localizedAlertOptions(array $options = []): array
    {
        $position = LaravelLocalization::getCurrentLocale() === 'ar' ? 'top-start' : 'top-end';

        return array_merge(['position' => $position], $options);
    }
}