<?php

return [
    'grant_type' => 'password',
    'client_id' => env('SMSTO_CLIENT_ID'),
    'client_secret' => env('SMSTO_CLIENT_SECRET'),
    'username'=> env('SMSTO_EMAIL'),
    'password' => env('SMSTO_PASSWORD'),
    'scope' => '*'
];