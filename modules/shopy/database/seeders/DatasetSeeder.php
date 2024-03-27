<?php

namespace Fpaipl\Shopy\Database\Seeders;

use Illuminate\Database\Seeder;
use Fpaipl\Shopy\Models\PickupAddress;

class DatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            // [
            //     'code' => 'FPAIPL10',
            //     'type' => 'percentage',
            //     'value' => 10,
            //     'max_value' => 10000,
            //     'min_value' => 0,
            //     'max_usage' => 100,
            //     'max_usage_per_user' => 1,
            //     'valid_from' => now(),
            //     'valid_to' => now()->addDays(30),
            //     'active' => true,
            //     'detail' => '10% off on all products',
            //     'applicable' => 'all', // 'all' or 'products'
            // ],
            // [
            //     'code' => 'FPAIPL20',
            //     'type' => 'percentage',
            //     'value' => 20,
            //     'max_value' => 20000,
            //     'min_value' => 0,
            //     'max_usage' => 100,
            //     'max_usage_per_user' => 1,
            //     'valid_from' => now(),
            //     'valid_to' => now()->addDays(30),
            //     'active' => true,
            //     'detail' => '20% off on all products',
            //     'applicable' => 'all', // 'all' or 'products'
            // ],
            // [
            //     'code' => 'Buy1Get1',
            //     'type' => 'percentage',
            //     'value' => 50,
            //     'max_value' => 50000,
            //     'min_value' => 0,
            //     'max_usage' => 100,
            //     'max_usage_per_user' => 1,
            //     'valid_from' => now(),
            //     'valid_to' => now()->addDays(30),
            //     'active' => true,
            //     'detail' => '50% off on all products',
            //     'applicable' => 'products', // 'all' or 'products'
            // ],
            // [
            //     'code' => 'CLUB100',
            //     'type' => 'percentage',
            //     'value' => 5,
            //     'max_value' => 100000,
            //     'min_value' => 1,
            //     'max_usage' => 100,
            //     'max_usage_per_user' => 1,
            //     'valid_from' => now(),
            //     'valid_to' => now()->addDays(30),
            //     'active' => true,
            //     'detail' => '5% off on all products',
            //     'applicable' => 'all', // 'all' or 'products'
            // ],
            [
                'code' => 'NEWUSER',
                'type' => 'fixed',
                'value' => 500,
                'max_value' => 500,
                'min_value' => 1,
                'max_usage' => 100,
                'max_usage_per_user' => 1,
                'valid_from' => now(),
                'valid_to' => now()->addDays(30),
                'active' => true,
                'detail' => '₹ 500 off on first order',
                'applicable' => 'all', // 'all' or 'products'
            ],
            [
                'code' => 'SHOPY100',
                'type' => 'fixed',
                'value' => 100,
                'max_value' => 100,
                'min_value' => 1,
                'max_usage' => 100,
                'max_usage_per_user' => 1,
                'valid_from' => now(),
                'valid_to' => now()->addDays(30),
                'active' => true,
                'detail' => '₹ 100 off on all products',
                'applicable' => 'all', // 'all' or 'products'
            ]
        ];

        foreach ($coupons as $coupon) {
            \Fpaipl\Shopy\Models\Coupon::create($coupon);
        }
    }
}
