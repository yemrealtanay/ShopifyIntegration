<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'shopify' => [
        'app_key' => env('SHOPIFY_APP_KEY'),
        'app_password' => env('SHOPIFY_APP_PASSWORD'),
        'app_scopes' => explode(',', env('SHOPIFY_APP_SCOPES')),
        'app_host' => env('SHOPIFY_APP_HOST'),
        'api_version' => env('SHOPIFY_API_VERSION'),
        'store_domain' => env('SHOPIFY_STORE_DOMAIN'),
    ],

    'google_cloud' => [
        'key_file' => env('GOOGLE_TRANSLATION_API_KEY_FILE_NAME'),
        'translation_default_target'=> env('GOOGLE_CLOUD_TRANSLATION_DEFAULT_TARGET', null),
    ],

];
