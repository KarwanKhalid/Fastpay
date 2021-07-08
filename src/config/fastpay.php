<?php

/**
 * Fastpay Configuration
 */

return [
    "projectPath" => env("PROJECT_PATH"),
    "apiDomain" => env("API_DOMAIN_URL"), // For Sandbox, use "https://dev.fast-pay.cash". For Live, use "https://secure.fast-pay.cash"
    "apiCredentials" => [
        "merchant_mobile_no" => env("MERCHANT_MOBILE_NO"),
        "store_password" => env("STORE_PASSWORD"),
    ],
    "apiUrl" => [
        "extra" => "/merchant/payment?token=",
        "make_payment" => "/merchant/generate-payment-token",
        "transaction_status" => "/merchant/payment/validation",
        "order_validate" => "/merchant/payment/validation",
        "refund_payment" => "/merchant/payment/validation",
        "refund_status" => "/merchant/payment/validation",
    ],
    "connect_from_localhost" => env("IS_LOCALHOST", true), // For Sandbox, use "true", For Live, use "false"
    "success_url" => "/success",
    "failed_url" => "/fail",
    "cancel_url" => "/cancel",
    "ipn_url" => "/ipn",
];

