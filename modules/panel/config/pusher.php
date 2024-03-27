<?php

return [
    'event' => env('PUSHER_EVENT', 'Test-event'),
    'channel' => env('PUSHER_CHANNEL', 'Test-channel'),
    'message' => env('PUSHER_MESSAGE', 'Reload Page to see new created data.'),
    'private-channel' => env('PUSHER_PRIVATE_CHANNEL', 'Test-private-channel-'),
];