<?php

return [
    'auth_mode' => env('SMSTO_AUTH_MODE', 'api_key'),
    'client_id' => env('SMSTO_CLIENT_ID'),
    'secret' => env('SMSTO_CLIENT_SECRET'),
    'api_key'=> env('SMSTO_API_KEY'),
    'token_expire_ttl'=> env('SMSTO_TOKEN_EXPIRE_TTL'),
    'enable_token_auto_refresh'=> env('SMSTO_TOKEN_AUTO_REFRESH', false),
    'auto_refresh_offset'=> env('SMSTO_AUTO_REFRESH_OFFSET', 30), // In Seconds

    'auth_url' => env('SMSTO_AUTH_URL', 'https://auth.sms.to'),
    'sms_url' => env('SMSTO_SMS_URL', 'https://api.sms.to'),
    'contact_url' => env('SMSTO_CONTACT_URL', 'https://sms.to'),
    'shortlink_url' => env('SMSTO_SHORTLINK_URL', 'https://sms.to'),
    'number_lookup_url' => env('SMSTO_NUMBER_LOOKUP_URL', 'https://sms.to'),
    'team_url' => env('SMSTO_TEAM_URL', 'https://sms.to'),
];
