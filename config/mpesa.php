<?php

return [
    'shortcode' => env('MPESA_SHORTCODE'),
    'lipa_na_mpesa_shortcode' => env('MPESA_LIPA_NA_SHORTCODE'),
    'lipa_na_mpesa_shortcode_password' => env('MPESA_LIPA_NA_SHORTCODE_PASSWORD'),
    'passkey' => env('MPESA_PASSKEY'),
    'lipa_na_mpesa_shortcut' => env('MPESA_LIPA_NA_SHORTCUT'),
    'lipana_callback_url' => env('MPESA_LIPANA_CALLBACK_URL'),
    'environment' => env('MPESA_ENVIRONMENT'), // "sandbox" or "live"
];
