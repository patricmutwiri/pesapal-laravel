<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/7/23, 3:18 PM
 * @twitter https://twitter.com/patric_mutwiri
 *
 */

return [
    /*
     * Supported: sandbox, production
     * */
    'pesapal-env' => env('PESAPAL_ENV', 'sandbox'),

    /*
     * Default currency in ISO format. Defaults to Kenya Shillings
     * Supported: KES, TZS etc
     * */
    'pesapal-currency' => env('PESAPAL_CURRENCY', 'KES'),

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
        'sandbox' => env('PESAPAL_SANDBOX_BASE', 'https://cybqa.pesapal.com/pesapalv3/'),
        'production' => env('PESAPAL_PRODUCTION_BASE', 'https://pay.pesapal.com/v3/'),
    ],

    /*
     * Endpoint configurations
     * */
    'pesapal-endpoint' => [
        /*
         * For getting auth token using key and secret
         * Your Pesapal merchant consumer_key and consumer_secret will be used to generate an access token. This access token is valid for a maximum period of 5 minutes. Use this token (sent as a Bearer Token) to access all other Pesapal API 3.0 endpoints.
         * */
        'auth' => env('PESAPAL_AUTH_ENDPOINT', 'api/Auth/RequestToken'),

        /*
         * For registering the url(s) to be used for receiving IPNs from pesapal
         * It's mandatory to have IPN configured to allow Pesapal to notify your servers when a status changes. It's also important to note that this IPN URL must be publicly available. In cases where you have strict server rules preventing external systems reaching your end, you must then whitelist all calls from our domain (pesapal.com).
         * */
        'ipn-register' => env('PESAPAL_IPN_REG_ENDPOINT', 'api/URLSetup/RegisterIPN'),

        /*
         * For Listing registered IPN URLs
         * This endpoint allows you to fetch all registered IPN URLs for a particular Pesapal merchant account.
         * */
        'ipn-list' => env('PESAPAL_IPN_LIST_ENDPOINT', 'api/URLSetup/GetIpnList'),

        /*
         * Making the actual payment/order request to pesapal.
         * Call the SubmitOrderRequest and in return you will get a response which contains a payment redirect URL which you then redirect the customer to or load the URL as an iframe within your site in case you don’t want to redirect the customer off your application.
         * */
        'payment-request' => env('PESAPAL_PAYMENT_REQ_ENDPOINT', 'api/Transactions/SubmitOrderRequest'),

        /*
         * Check the status of a transaction using a given id
         * Once Pesapal redirect your customer to your callback URL and triggers your IPN URL, you need to check the status of the payment using the OrderTrackingId.
         * */
        'tsq' => env('PESAPAL_TSQ_ENDPOINT', 'api/Transactions/GetTransactionStatus'),
    ],

    /*
     * The URL to receive IPNs from pesapal.
     * */
    'pesapal-ipn' => env('PESAPAL_IPN', config('app.url').'/pesapal/ipn'),

    /*
     * The URL to receive callback payload from pesapal.
     * */
    'pesapal-callback' => env('PESAPAL_CALLBACK', config('app.url').'/pesapal/callback'),
];