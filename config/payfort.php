<?php
return [
    'use_test_env' => env('PAYFORT_USE_TEST_ENV', false),
    'sandbox' => env('PAYFORT_USE_SANDBOX', false),
    'merchant_identifier' => env('PAYFORT_MERCHANT_IDENTIFIER'),
    'access_code' => env('PAYFORT_ACCESS_CODE'),
    'sha_type' => env('PAYFORT_SHA_TYPE', 'sha256'),
    'sha_request_phrase' => env('PAYFORT_SHA_REQUEST_PHRASE'),
    'sha_response_phrase' => env('PAYFORT_SHA_RESPONSE_PHRASE'),
    'currency' => env('PAYFORT_CURRENCY', 'USD'),
    'return_url' => env('PAYFORT_RETURN_URL', '/')
];