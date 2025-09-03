<?php

return [
'publicKey' => env('FLUTTERWAVE_PUBLIC_KEY'),
'secretKey' => env('FLUTTERWAVE_SECRET_KEY'),
'secretHash' => env('FLUTTERWAVE_SECRET_HASH'),
'encryptionKey' => env('FLUTTERWAVE_ENCRYPTION_KEY'),
'environment' => env('FLUTTERWAVE_ENVIRONMENT', 'staging'), 
'paymentUrl' => env('FLUTTERWAVE_ENVIRONMENT', 'staging') === 'live'
? 'https://www.google.com/search?q=https://api.flutterwave.com/v3'
: 'https://www.google.com/search?q=https://api.flutterwave.com/v3',
];