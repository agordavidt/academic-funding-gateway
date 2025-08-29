<?php


return [
    'acceptance_fee' => env('ACCEPTANCE_FEE', 3000),
    'max_grant_amount' => env('MAX_GRANT_AMOUNT', 500000),
    'currency' => 'NGN',
    
    'file_uploads' => [
        'max_size' => 2048, // KB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
        'passport_photo' => [
            'max_size' => 1024,
            'allowed_types' => ['jpg', 'jpeg', 'png']
        ]
    ],
    
    'flutterwave' => [
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_hash' => env('FLUTTERWAVE_SECRET_HASH'),
        'base_url' => env('FLUTTERWAVE_BASE_URL', 'https://api.flutterwave.com/v3'),
    ],
    
    'sms' => [
        'provider' => env('SMS_PROVIDER', 'twilio'), // twilio, termii
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'from_number' => env('TWILIO_FROM_NUMBER')
        ]
    ],
    
    'import' => [
        'batch_size' => 100,
        'allowed_formats' => ['csv', 'xlsx', 'json']
    ],
    
    'security' => [
        'login_attempts' => 5,
        'lockout_duration' => 15, // minutes
        'password_reset_expires' => 60, // minutes
        'session_timeout' => 120, // minutes
    ]
];

