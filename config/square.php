<?php

return [
    'access_token' => env('SQUARE_ACCESS_TOKEN'),
    'environment' => env('SQUARE_ENVIRONMENT', 'sandbox'),
    'location_id' => env('SQUARE_LOCATION_ID'),
    'application_id' => env('SQUARE_APPLICATION_ID'),
];