<?php
return [
    'api_key' => env('HITPAY_API_KEY'),
    'api_salt' => env('HITPAY_API_SALT'),
    'api_base_url' => env('HITPAY_API_BASE_URL'),
    'pricing' => [
        'printing' => [
            'a3' => [
                'mono' => [
                    'single' => 1.00,
                    'duplex' => 1.50,
                ],
                'color' => [
                    'single' => 2.00,
                    'duplex' => 3.00,
                ]
            ],
            'a4' => [
                'mono' => [
                    'single' => 0.50,
                    'duplex' => 0.75,
                ],
                'color' => [
                    'single' => 1.00,
                    'duplex' => 1.50,
                ]
            ],
            'a5' => [
                'mono' => [
                    'single' => 0.50,
                    'duplex' => 0.75,
                ],
                'color' => [
                    'single' => 1.00,
                    'duplex' => 1.50,
                ]
            ]
        ]
    ]
];
