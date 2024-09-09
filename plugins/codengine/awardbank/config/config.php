<?php
return [
    'xero' => [
        'oauth' => [
            'callback' => env('XERO_OAUTH_CALLBACK', ''),
            'consumer_key' => env('XERO_OAUTH_CONSUMER_KEY', ''),
            'consumer_secret' => env('XERO_OAUTH_CONSUMER_SECRET', ''),
            'rsa_private_key' => 'file://plugins/codengine/awardbank/certs/privatekey.pem',
        ],
    ],
    'payway' => [
            'publishableAPI' => env('PAYWAY_PUBLISHABLE_API', ''),
            'secretAPI' => env('PAYWAY_SECRET_API', ''),
            'form_params' => [
                'merchantId' => env('PAYWAY_MERCHANTID', ''),
            ],
    ]
];
