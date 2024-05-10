<?php

return [

    'api' => [
        'cache' => [
            'enabled' => false,
            'duration' => 60 * 60 * 24 * 7, // 1 week
        ],
    ],

    'uia' => 'https://ui-avatars.com/api/?name=',

    'default_product_image' => 'https://via.placeholder.com/150',

    // new user from wsg app, i.e. frontend
    'registerable-roles' => [
        [
            'id' => 'user',
            'name' => 'User',
        ]
    ], 

    'assignable-roles' => [],

    'roles' => [
        [
            'id' => 'admin',
            'name' => 'Admin',
        ],
        [
            'id' => 'user',
            'name' => 'user',
        ],
        [
            'id' => 'manager',
            'name' => 'manager',
        ],
        [
            'id' => 'data-manager',
            'name' => 'data-manager',
        ],
        [
            'id' => 'store-manager',
            'name' => 'store-manager',
        ],
        [
            'id' => 'order-manager',
            'name' => 'order-manager',
        ],
    ],

    'create-actions' => [],

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
            'id' => 'menu-brand',
            'icon' => 'bi bi-star',
            'name' => 'Wsg Brands',
            'route' => 'wsg-brands.index',
            'access' => 'manager|store-manager',
            'child' => [],
        ],
        
        [
            'module' => 'Working Modules',
            'access' => 'admin|manager|data-manager',
            'child' => [],
        ],
        [
            'id' => 'menu-order',
            'icon' => 'bi bi-cart-check',
            'name' => 'Orders',
            'route' => 'orders.index',
            'access' => 'manager|order-manager',
            'child' => [],
        ],
        [
            'id' => 'menu-receipts',
            'icon' => 'bi bi-bank',
            'name' => 'Receipts',
            'route' => 'payments.index',
            'access' => 'manager|account-manager',
            'child' => [],
        ],
        [
            'id' => 'menu-delivery',
            'icon' => 'bi bi-truck',
            'name' => 'Delivery',
            'route' => 'deliveries.index',
            'access' => 'manager|store-manager',
            'child' => [],
        ],


        [
            'id' => 'menu-people',
            'icon' => 'bi bi-people',
            'name' => 'People',
            'route' => null,
            'position' => 2,
            'access' => 'admin|manager|data-manager',
            'child' => [
                [
                    'icon' => 'bi bi-person',
                    'name' => 'New Accounts',
                    'route' => 'new-accounts.index',
                    'position' => 1,
                    'access' => 'admin|manager|data-manager',
                ],
                [
                    'icon' => 'bi bi-person',
                    'name' => 'All Accounts',
                    'route' => 'accounts.index',
                    'position' => 2,
                    'access' => 'admin|manager|data-manager',
                ],
            ],
        ],


        [
            'module' => 'Database Modules',
            'access' => 'admin|manager|data-manager',
            'child' => [],
        ],

        [
            'id' => 'menu-structure',
            'icon' => 'bi bi-star',
            'name' => 'Products',
            'route' => 'products.index',
            'position' => 2,
            'access' => 'admin|manager-brand|data-manager',
            'child' => [],
        ],
        [
            'id' => 'menu-structure',
            'icon' => 'bi bi-ticket',
            'name' => 'Coupons',
            'route' => 'coupons.index',
            'position' => 2,
            'access' => 'admin|manager-brand|data-manager',
            'child' => [],
        ],

        [
            'id' => 'menu-structure',
            'icon' => 'bi bi-diagram-3',
            'name' => 'Manage Data',
            'route' => null,
            'position' => 2,
            'access' => 'admin|manager-brand|data-manager',
            'child' => [
                [
                    'name' => 'Brands',
                    'route' => 'brands.index',
                    'access' => 'admin|manager-brand|data-manager',
                ],
                [
                    'name' => 'Categories',
                    'route' => 'categories.index',
                    'access' => 'admin|manager-brand|data-manager',
                ],
                [
                    'name' => 'Collections',
                    'route' => 'collections.index',
                    'access' => 'admin|manager-brand|data-manager',
                ],
                [
                    'name' => 'Taxes',
                    'route' => 'taxes.index',
                    'access' => 'admin|manager-brand|data-manager',
                ],
            ],
        ],

       
        // management modules
    //     [
    //         'module' => 'Management Modules',
    //         'access' => 'admin',
    //         'child' => [],
    //     ],
    //     [
    //         'id' => 'menu-system',
    //         'icon' => 'bi bi-shield-check',
    //         'name' => 'System Controls',
    //         'route' => null,
    //         'position' => 6,
    //         'access' => 'admin',
    //         'child' => [
    //             [
    //                 'icon' => 'bi bi-arrow-right-short text-white',
    //                 'name' => 'Users',
    //                 'route' => 'users.index',
    //                 'position' => 1,
    //                 'access' => 'admin',
    //             ],
    //             [
    //                 'icon' => 'bi bi-arrow-right-short text-white',
    //                 'name' => 'Notifications',
    //                 'route' => 'notifications.index',
    //                 'position' => 2,
    //                 'access' => 'admin',
    //             ],
    //             [
    //                 'icon' => 'bi bi-arrow-right-short text-white',
    //                 'name' => 'Activity Logs',
    //                 'route' => 'activitylogs.index',
    //                 'position' => 3,
    //                 'access' => 'admin',
    //             ],
    //             [
    //                 'icon' => 'bi bi-arrow-right-short text-white',
    //                 'name' => 'Queued Jobs',
    //                 'route' => 'jobs.index',
    //                 'position' => 3,
    //                 'access' => 'admin',
    //             ],
    //             [
    //                 'icon' => 'bi bi-arrow-right-short text-white',
    //                 'name' => 'Failed Jobs',
    //                 'route' => 'failedjobs.index',
    //                 'position' => 4,
    //                 'access' => 'admin',
    //             ],
    //             [
    //                 'icon' => 'bi bi-arrow-right-short text-white',
    //                 'name' => 'Pusher Initiate',
    //                 'route' => 'pusher.push',
    //                 'position' => 5,
    //                 'access' => 'admin',
    //             ],
    //             [
    //                 'icon' => 'bi bi-arrow-right-short text-white',
    //                 'name' => 'WPSubscriptions',
    //                 'route' => 'webpushs.index',
    //                 'position' => 6,
    //                 'access' => 'admin',
    //             ],
    //         ],
    //     ],
    ],

    'applinks' => [
        [
            'icon' => 'bi bi-search',
            'name' => 'Search Action',
            'route' => 'actions.search',
            'access' => 'user-role',
        ],
        [
            'icon' => 'bi bi-download',
            'name' => 'Install App',
            'action' => 'installPWA();',
        ],
    ],

    'userlinks' => [
        [
            'icon' => 'bi bi-person-circle',
            'name' => 'My Profile',
            'route' => 'profiles.show',
            'access' => '',
        ],
    ],

    'defaultinks' => [
        1 => [
            'icon' => 'bi bi-',
            'name' => 'About Us',
            'route' => 'about-us',
            'access' => '',
        ],
        2 => [
            'icon' => 'bi bi-',
            'name' => 'Terms & Conditions',
            'route' => 'terms-and-conditions',
            'access' => '',
        ],
    ],

];











// 'registerable-roles' => [
//     [
//         'id' => 'user-brand',
//         'name' => 'User Brand',
//     ],
//     [
//         'id' => 'user-vendor',
//         'name' => 'User Vendor',
//     ],
// ],

// 'assignable-roles' => [
//     'user-brand' => [
//         [
//             'grade' => 'C',
//             'id' => 'data-manager',
//             'name' => 'Data Manager',
//             'description' => 'Can manage data',
//         ],
//         [
//             'grade' => 'C',
//             'id' => 'order-manager-brand',
//             'name' => 'Order Manager Brand',
//             'description' => 'Can manage orders of brand',
//         ],
//         [
//             'grade' => 'C',
//             'id' => 'store-manager-brand',
//             'name' => 'Store Manager Brand',
//             'description' => 'Can manage stores of brand',
//         ],
//         [
//             'grade' => 'C',
//             'id' => 'account-manager-brand',
//             'name' => 'Account Manager Brand',
//             'description' => 'Can manage accounts of brand',
//         ],
//         [
//             'grade' => 'B',
//             'id' => 'manager-brand',
//             'name' => 'Manager Brand',
//             'description' => 'Can manage brand',
//         ],
//         [
//             'grade' => 'A',
//             'id' => 'owner-brand',
//             'name' => 'Owner Brand',
//             'description' => 'Can control brand',
//         ],
//     ],
//     'user-vendor' => [
//         [
//             'grade' => 'C',
//             'id' => 'order-manager-vendor',
//             'name' => 'Order Manager Vendor',
//             'description' => 'Can manage orders of vendor',
//         ],
//         [
//             'grade' => 'C',
//             'id' => 'store-manager-vendor',
//             'name' => 'Store Manager Vendor',
//             'description' => 'Can manage stores of vendor',
//         ],
//         [
//             'grade' => 'C',
//             'id' => 'account-manager-vendor',
//             'name' => 'Account Manager Vendor',
//             'description' => 'Can manage accounts of vendor',
//         ],
//         [
//             'grade' => 'B',
//             'id' => 'manager-vendor',
//             'name' => 'Manager Vendor',
//             'description' => 'Can manage vendor',
//         ],
//         [
//             'grade' => 'A',
//             'id' => 'owner-vendor',
//             'name' => 'Owner Vendor',
//             'description' => 'Can control vendor',
//         ],
//     ],
//     'admin' => [
//             [
//                 'grade' => 'C',
//                 'id' => 'order-manager-brand',
//                 'name' => 'Order Manager Brand',
//                 'description' => 'Can manage orders of brand',
//             ],
//             [
//                 'grade' => 'C',
//                 'id' => 'store-manager-brand',
//                 'name' => 'Store Manager Brand',
//                 'description' => 'Can manage stores of brand',
//             ],
//             [
//                 'grade' => 'C',
//                 'id' => 'account-manager-brand',
//                 'name' => 'Account Manager Brand',
//                 'description' => 'Can manage accounts of brand',
//             ],
//             [
//                 'grade' => 'B',
//                 'id' => 'manager-brand',
//                 'name' => 'Manager Brand',
//                 'description' => 'Can manage brand',
//             ],
//             [
//                 'grade' => 'A',
//                 'id' => 'owner-brand',
//                 'name' => 'Owner Brand',
//                 'description' => 'Can control brand',
//             ],
//             [
//                 'grade' => 'C',
//                 'id' => 'order-manager-vendor',
//                 'name' => 'Order Manager Vendor',
//                 'description' => 'Can manage orders of vendor',
//             ],
//             [
//                 'grade' => 'C',
//                 'id' => 'store-manager-vendor',
//                 'name' => 'Store Manager Vendor',
//                 'description' => 'Can manage stores of vendor',
//             ],
//             [
//                 'grade' => 'C',
//                 'id' => 'account-manager-vendor',
//                 'name' => 'Account Manager Vendor',
//                 'description' => 'Can manage accounts of vendor',
//             ],
//             [
//                 'grade' => 'B',
//                 'id' => 'manager-vendor',
//                 'name' => 'Manager Vendor',
//                 'description' => 'Can manage vendor',
//             ],
//             [
//                 'grade' => 'A',
//                 'id' => 'owner-vendor',
//                 'name' => 'Owner Vendor',
//                 'description' => 'Can control vendor',
//             ],
//     ],
// ],

// 'roles' => [
//     [
//         'id' => 'admin',
//         'name' => 'Admin',
//     ],
//     [
//         'id' => 'user',
//         'name' => 'user',
//     ],
//    // mandatory for all
//     [
//         'id' => 'user-brand',
//         'name' => 'User Brand',
//     ],
//     [
//         'id' => 'user-vendor',
//         'name' => 'User Vendor',
//     ],
//     // for reporting purpose
//     [
//         'id' => 'owner-brand',
//         'name' => 'Owner Brand',
//     ],
//     [
//         'id' => 'owner-vendor',
//         'name' => 'Owner Vendor',
//     ],
//     // for general purpose
//     [
//         'id' => 'manager-brand',
//         'name' => 'Manager Brand',
//     ],
//     [
//         'id' => 'manager-vendor',
//         'name' => 'Manager Vendor',
//     ],
//     // for specific purpose
//     [
//         'id' => 'order-manager-brand',
//         'name' => 'Order Manager Brand',
//     ],
//     [
//         'id' => 'store-manager-brand',
//         'name' => 'Store Manager Brand',
//     ],
//     [
//         'id' => 'account-manager-brand',
//         'name' => 'Account Manager Brand',
//     ],
//     [
//         'id' => 'order-manager-vendor',
//         'name' => 'Order Manager Vendor',
//     ],
//     [
//         'id' => 'store-manager-vendor',
//         'name' => 'Store Manager Vendor',
//     ],
//     [
//         'id' => 'account-manager-vendor',
//         'name' => 'Account Manager Vendor',
//     ],
//     // for data purpose
//     [
//         'id' => 'data-manager',
//         'name' => 'Data Manager',
//     ],
// ],
