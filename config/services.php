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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'twilio' => [
        // Prefer the TWILIO_ACCOUNT_SID name (matches README/docs). Fall back to older TWILIO_SID if present.
        'sid' => env('TWILIO_ACCOUNT_SID', env('TWILIO_SID')),
        // Auth token (both names are commonly used; prefer TWILIO_AUTH_TOKEN)
        'auth_token' => env('TWILIO_AUTH_TOKEN', env('TWILIO_AUTH')),
        // WhatsApp from number. Prefer TWILIO_WHATSAPP_FROM, fall back to TWILIO_WHATSAPP_NUMBER
        'whatsapp_number' => env('TWILIO_WHATSAPP_FROM', env('TWILIO_WHATSAPP_NUMBER')),
    ],

    'whatsapp' => [
        /*
        |--------------------------------------------------------------------------
        | WhatsApp Provider
        |--------------------------------------------------------------------------
        |
        | Choose your WhatsApp provider:
        | - 'twilio': Use Twilio WhatsApp API
        | - 'whatsapp_business': Use Meta WhatsApp Business API
        | - 'generic': Use a generic HTTP API
        |
        */
        'provider' => env('WHATSAPP_PROVIDER', 'twilio'),

        /*
        |--------------------------------------------------------------------------
        | Twilio Configuration
        |--------------------------------------------------------------------------
        |
        | Get these from https://www.twilio.com/console
        | WhatsApp From number format: whatsapp:+14155238886
        |
        */
        'twilio_account_sid' => env('TWILIO_ACCOUNT_SID'),
        'twilio_auth_token' => env('TWILIO_AUTH_TOKEN'),
        'twilio_whatsapp_from' => env('TWILIO_WHATSAPP_FROM'),

        /*
        |--------------------------------------------------------------------------
        | WhatsApp Business API Configuration (Meta)
        |--------------------------------------------------------------------------
        |
        | Get these from https://developers.facebook.com/apps
        | Requires WhatsApp Business Account setup
        |
        */
        'whatsapp_business_token' => env('WHATSAPP_BUSINESS_TOKEN'),
        'whatsapp_business_phone_id' => env('WHATSAPP_BUSINESS_PHONE_ID'),
        'whatsapp_business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),

        /*
        |--------------------------------------------------------------------------
        | Generic API Configuration
        |--------------------------------------------------------------------------
        |
        | For custom WhatsApp API providers
        |
        */
        'api_url' => env('WHATSAPP_API_URL'),
        'api_key' => env('WHATSAPP_API_KEY'),
    ],

];
