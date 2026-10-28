<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY','AIzaSyCIJH6jbULC8MVaAX2LyxIQEBwWPjjeIF0'),
    ],
    'google_maps_tracking' => [
        'api_key' => env('GOOGLE_MAPS_TRACKING_API_KEY'),
    ],

    'moyasar' => [
        'key' => env('MOYASAR_API_PUBLIC'),
        'secret' => env('MOYASAR_API_SECRET'),
        'url' => env("MOYASAR_API_URL"),
    ],
    'clickpay' => [
        'server' => env('CLICKPAY_API_SERVER'),
        'key' => env('CLICKPAY_API_SERVER'),
        'server_key' => env('CLICKPAY_API_SERVER'),
        'client' => env('CLICKPAY_API_CLIENT'),
        'profile_id' => env('CLICKPAY_API_PROFILE'),
        'url' => env("CLICKPAY_API_URL"),
        'currency' => env("clickpay_currency", 'SAR'),
    ],
    'messegat' => [
        'username' => env("MESSEGAT_USER_NAME"),
        'apikey' =>  env("MESSEGAT_API_KEY"),
        'senderid' =>  env("MESSEGAT_SENDER_ID"),
    ]

];
