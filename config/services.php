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

    'eventbrite' => [
        'private_token' => env('EVENTBRITE_PRIVATE_TOKEN', 'XVFMDFDDXRTMZHIQ4EBK'),
        'api_key' => env('EVENTBRITE_API_KEY', '4CZSGWX2CVFLPLRW5M'),
        'client_secret' => env('EVENTBRITE_CLIENT_SECERET','MFCFRHW72AQWY6SZJMEJV74C3WPH227NIPK6XAKYH4P6LOSLT4'),
        'public_token' => env('EVENTBRITE_PUBLIC_TOKEN','H6TV75JPQXTL364MWOAP'),
    ],
];
