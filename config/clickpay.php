<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ClickPay API Key
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this in your .env file, as it will be used to contact
    | ClickPay servers
    |
    |
    */

    'key' => env('CLICKPAY_API_SERVER'),
    'server_key' => env('CLICKPAY_API_SERVER'),


    /*
    |--------------------------------------------------------------------------
    | ClickPay Publishable API Key
    |--------------------------------------------------------------------------
    |
    | This key is used for payment forms on the frontend
    |
    |
    |
    */

    'publishable_key' => env('CLICKPAY_API_CLIENT'),
    'profile_id' => env('CLICKPAY_API_PROFILE'),

    /*
   |--------------------------------------------------------------------------
   | Currency
   |--------------------------------------------------------------------------
   |
   | The currency you registered in with ClickPay account
     you must pass value from this array ['SAR','AED','EGP','OMR','JOD','US']
   |
   */

    'currency' => env('clickpay_currency', 'SAR'),
    /*
   |--------------------------------------------------------------------------
   | Currency
   |--------------------------------------------------------------------------
   |
   | The currency you registered in with ClickPay account
     you must pass value from this array ['SAR','AED','EGP','OMR','JOD','US']
   |
   */

    'callback' => env('clickpay_ipn_callback', App\Domain\AppBase\Actions\Payment\ClickPayAction::class),


];
