<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/4/23, 11:44 PM
 * @twitter https://twitter.com/patricmutwiri
 *
 */

return [
    /*
     * Supported: sandbox, production
     * */
    'pesapal-env' => env('PESAPAL_ENV', 'sandbox'),

    /*
     * Defaults to key from test env
     * */
    'pesapal-key' => env('PESAPAL_KEY', 'qkio1BGGYAXTu2JOfm7XSXNruoZsrqEW'),

    /*
     * Defaults to secret from test env
     * */
    'pesapal-secret' => env('PESAPAL_SECRET', 'osGQ364R49cXKeOYSpaOnT++rHs='),

    /*
     * URLs to services depending on environment
     * */
    'pesapal-base' => [
        'sandbox' => env('PESAPAL_SANDBOX_BASE', 'https://cybqa.pesapal.com/pesapalv3'),
        'production' => env('PESAPAL_PRODUCTION_BASE', 'https://pay.pesapal.com/v3'),
    ],

    /*
     * Endpoint configurations
     * */
    'pesapal-endpoint' => [
        'auth' => env('PESAPAL_AUTH_ENDPOINT', '/api/Auth/RequestToken'),
        'ipn-register' => env('PESAPAL_IPN_REG_ENDPOINT', '/api/URLSetup/RegisterIPN'),
        'ipn-list' => env('PESAPAL_IPN_LIST_ENDPOINT', '/apiURLSetup/GetIpnList'),
        'payment-request' => env('PESAPAL_PAYMENT_REQ_ENDPOINT', '/api/Transactions/SubmitOrderRequest'),
        'tsq' => env('PESAPAL_TSQ_ENDPOINT', '/api/Transactions/GetTransactionStatus'),
    ]
];