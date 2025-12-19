<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Format
    |--------------------------------------------------------------------------
    |
    | The default format for QR codes. Available formats: png, svg, eps
    |
    */
    'format' => env('QRCODE_FORMAT', 'svg'),

    /*
    |--------------------------------------------------------------------------
    | Default Size
    |--------------------------------------------------------------------------
    |
    | The default size for QR codes in pixels
    |
    */
    'size' => env('QRCODE_SIZE', 300),

    /*
    |--------------------------------------------------------------------------
    | Default Margin
    |--------------------------------------------------------------------------
    |
    | The default margin for QR codes in pixels
    |
    */
    'margin' => env('QRCODE_MARGIN', 10),

    /*
    |--------------------------------------------------------------------------
    | Default Error Correction Level
    |--------------------------------------------------------------------------
    |
    | The default error correction level. Available levels:
    | L (Low), M (Medium), Q (Quartile), H (High)
    |
    */
    'errorCorrectionLevel' => env('QRCODE_ERROR_CORRECTION', 'M'),

    /*
    |--------------------------------------------------------------------------
    | Default Encoding
    |--------------------------------------------------------------------------
    |
    | The default character encoding
    |
    */
    'encoding' => env('QRCODE_ENCODING', 'UTF-8'),

    /*
    |--------------------------------------------------------------------------
    | Colors
    |--------------------------------------------------------------------------
    |
    | Default colors for QR code foreground and background
    |
    */
    'colors' => [
        'foreground' => env('QRCODE_FOREGROUND_COLOR', '#000000'),
        'background' => env('QRCODE_BACKGROUND_COLOR', '#FFFFFF'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Settings
    |--------------------------------------------------------------------------
    |
    | Settings for storing QR codes
    |
    */
    'storage' => [
        'disk' => env('QRCODE_STORAGE_DISK', 'public'),
        'path' => env('QRCODE_STORAGE_PATH', 'qrcodes'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Settings
    |--------------------------------------------------------------------------
    |
    | Settings specific to SPPD application
    |
    */
    'sppd' => [
        'enabled' => env('QRCODE_SPPD_ENABLED', true),
        'size' => env('QRCODE_SPPD_SIZE', 150),
        'include_logo' => env('QRCODE_SPPD_INCLUDE_LOGO', false),
        'logo_path' => env('QRCODE_SPPD_LOGO_PATH', 'images/logo.png'),
    ],

    'surat_perintah' => [
        'enabled' => env('QRCODE_SPT_ENABLED', true),
        'size' => env('QRCODE_SPT_SIZE', 150),
        'include_logo' => env('QRCODE_SPT_INCLUDE_LOGO', false),
        'logo_path' => env('QRCODE_SPT_LOGO_PATH', 'images/logo.png'),
    ],
];
