<?php

return [
   
    'event' => env('PUSHER_APP_EVENT', 'Test-event'),
    'channel' => env('PUSHER_APP_CHANNEL', 'Test-channel'),
    'private-channel' => env('PUSHER_PRIVATE_CHANNEL', 'private-Test-channel'),
    'private-event' => env('PUSHER_PRIVATE_EVENT', 'private-Test-event'),
    'presence-channel' => env('PUSHER_PRESENCE_CHANNEL', 'presence-Test-channel'),
    'presence-event' => env('PUSHER_PRESENCE_EVENT', 'presence-Test-event'),
    'message' => 'Hello from Pusher!',

    'app_id' => env('PUSHER_APP_ID'),
    'app_key' => env('PUSHER_APP_KEY'),
    'app_secret' => env('PUSHER_APP_SECRET'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true,
    ],
];
