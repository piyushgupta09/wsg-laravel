<?php

return [

    'assignable-roles' => [],

    'roles' => [
        // for administrative purpose only
        [
            'id' => 'admin',
            'name' => 'Admin',
        ],
       // mandatory for all
        [
            'id' => 'user',
            'name' => 'User',
        ],
    ],

    'actionlinks' => [],

    'modulelinks' => [
        [
            'id' => 'menu-dashboard',
            'icon' => 'bi bi-speedometer2',
            'name' => 'Dashboard',
            'route' => 'panel.dashboard', // default 'panel.dashboard'
            'position' => 1,
            'access' => 'user',
            'child' => [],
        ],
        [
            'module' => 'Management Modules',
            'access' => 'manager|order-manager|store-manager|account-manager',
            'child' => [],
        ],
        [
            'id' => 'menu-system',
            'icon' => 'bi bi-shield-check',
            'name' => 'System Controls',
            'route' => null,
            'position' => 6,
            'access' => 'admin',
            'child' => [
                [
                    'icon' => 'bi bi-arrow-right-short text-white',
                    'name' => 'Users',
                    'route' => 'users.index',
                    'position' => 1,
                    'access' => 'admin',
                ],
                [
                    'icon' => 'bi bi-arrow-right-short text-white',
                    'name' => 'Notifications',
                    'route' => 'notifications.index',
                    'position' => 2,
                    'access' => 'admin',
                ],
                [
                    'icon' => 'bi bi-arrow-right-short text-white',
                    'name' => 'Activity Logs',
                    'route' => 'activitylogs.index',
                    'position' => 3,
                    'access' => 'admin',
                ],
                [
                    'icon' => 'bi bi-arrow-right-short text-white',
                    'name' => 'Queued Jobs',
                    'route' => 'jobs.index',
                    'position' => 3,
                    'access' => 'admin',
                ],
                [
                    'icon' => 'bi bi-arrow-right-short text-white',
                    'name' => 'Failed Jobs',
                    'route' => 'failedjobs.index',
                    'position' => 4,
                    'access' => 'admin',
                ],
                [
                    'icon' => 'bi bi-arrow-right-short text-white',
                    'name' => 'Pusher Initiate',
                    'route' => 'pusher.push',
                    'position' => 5,
                    'access' => 'admin',
                ],
                [
                    'icon' => 'bi bi-arrow-right-short text-white',
                    'name' => 'WPSubscriptions',
                    'route' => 'webpushs.index',
                    'position' => 6,
                    'access' => 'admin',
                ],
            ],
        ],

    ],

    'applinks' => [],

    'userlinks' => [],

    'defaultinks' => [
        1 => [
            'icon' => 'bi bi-person-circle',
            'name' => 'My Profile',
            'route' => 'profiles.show',
            'access' => '',
        ],
        2 => [
            'icon' => 'bi bi-',
            'name' => 'About Us',
            'route' => 'about-us',
            'access' => '',
        ],
        3 => [
            'icon' => 'bi bi-',
            'name' => 'Terms & Conditions',
            'route' => 'terms-and-conditions',
            'access' => '',
        ],
    ],

];
