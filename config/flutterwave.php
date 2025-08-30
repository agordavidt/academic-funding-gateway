<?php

return [
    'publicKey' => env('FLUTTERWAVE_PUBLIC_KEY'),
    'secretKey' => env('FLUTTERWAVE_SECRET_KEY'),
    'secretHash' => env('FLUTTERWAVE_SECRET_HASH'),
    'environment' => env('FLUTTERWAVE_ENVIRONMENT', 'staging'), // staging or live
    'paymentUrl' => env('FLUTTERWAVE_ENVIRONMENT', 'staging') === 'live' 
        ? 'https://api.flutterwave.com/v3/payments' 
        : 'https://ravesandboxapi.flutterwave.com/v3/payments',
];