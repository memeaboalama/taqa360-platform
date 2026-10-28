<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timout:
    |
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */
//
//    'pdf' => [
//        'enabled' => true,
//        'binary'  => env('WKHTML_PDF_BINARY', '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf"'),
//        'timeout' => false,
//        'options' => [],
//        'env'     => [],
//    ],
//
//    'image' => [
//        'enabled' => true,
//        'binary'  => env('WKHTML_IMG_BINARY', '"C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage"'),
//        'timeout' => false,
//        'options' => [],
//        'env'     => [],
//    ],


    'pdf' => [
        'enabled' => true,
        'binary' => env('WKHTML_PDF_BINARY', PHP_OS_FAMILY === 'Windows'
            ? (file_exists('C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe')
                ? '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"'
                : (file_exists('C:\Program Files (x86)\wkhtmltopdf\bin\wkhtmltopdf.exe')
                    ? '"C:\Program Files (x86)\wkhtmltopdf\bin\wkhtmltopdf.exe"'
                    : 'wkhtmltopdf.exe'))
            : base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64')),
        'timeout' => false,
        'options' => [
            'load-error-handling' => 'ignore',
            'load-media-error-handling' => 'ignore',
        ],
        'env' => [],
    ],

    'image' => [
        'enabled' => true,
        'binary' => env('WKHTML_IMG_BINARY', PHP_OS_FAMILY === 'Windows'
            ? (file_exists('C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage.exe')
                ? '"C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage.exe"'
                : (file_exists('C:\Program Files (x86)\wkhtmltopdf\bin\wkhtmltoimage.exe')
                    ? '"C:\Program Files (x86)\wkhtmltopdf\bin\wkhtmltoimage.exe"'
                    : 'wkhtmltoimage.exe'))
            : base_path('vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64')),
        'timeout' => false,
        'options' => [],
        'env' => [],
    ],

];
