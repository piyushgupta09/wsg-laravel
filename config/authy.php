<?php

return [
    
    'altdb' => env('DB_ALT_CONNECTION', 'mysql'),

    'registeration' => [
        'server' => env('AUTHY_REGISTERATION_SERVER', true),
        'website' => env('AUTHY_REGISTERATION_WEBSITE', false),
    ],

];