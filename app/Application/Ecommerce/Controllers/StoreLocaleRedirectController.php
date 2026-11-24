<?php

namespace App\Application\Ecommerce\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class StoreLocaleRedirectController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $supportedLocales = LaravelLocalization::getSupportedLocales();
        $locale = config('app.locale', 'ar');

        foreach ([$request->session()->get('locale'), $request->cookie('locale')] as $preferredLocale) {
            if (is_string($preferredLocale) && isset($supportedLocales[$preferredLocale])) {
                $locale = $preferredLocale;
                break;
            }
        }

        return redirect()->to(LaravelLocalization::getLocalizedURL($locale, $request->fullUrl(), [], true));
    }
}
