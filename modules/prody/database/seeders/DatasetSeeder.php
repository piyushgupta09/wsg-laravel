<?php

namespace Fpaipl\Prody\Database\Seeders;

use Fpaipl\Prody\Models\Tax;
use Fpaipl\Prody\Models\Brand;
use Illuminate\Database\Seeder;
use Fpaipl\Prody\Models\WsgBrand;
use Illuminate\Support\Facades\Log;

class DatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wsgBrands = [
            [ 
                'name' => 'Metro Fashion',
                'info' => 'Women\'s Fast Fashion',
                'server' => 'https://app.wsgbrand.in/api/metro-fashion/',
                'brands' => [
                    [ 
                        'name' => 'Deshigirl', 
                        'image' => asset('storage/assets/brands/deshigirl.jpg'),
                    ],
                    [ 
                        'name' => 'MacHiddle', 
                        'image' => asset('storage/assets/brands/machiddle.jpg'),
                    ],
                    [ 
                        'name' => 'Dnb Fashion', 
                        'image' => asset('storage/assets/brands/dnbfashion.jpg'),
                    ],
                ],
            ],
        ];

        // 'https://img.freepik.com/free-vector/bird-colorful-logo-gradient-vector_343694-1365.jpg'

        foreach ($wsgBrands as $wsgBrandData) {
            $validated = WsgBrand::validate($wsgBrandData);
            $createdWsgBrand = WsgBrand::create($validated);        
            if (isset($wsgBrandData['brands'])) {
                foreach ($wsgBrandData['brands'] as $brandData) {
                    $newBrand = new Brand();
                    $newBrand->name = $brandData['name'];
                    $newBrand->wsg_brand_id = $createdWsgBrand->id;
                    $newBrand->save();

                    $imagePath = public_path('storage/assets/brands/') . basename($brandData['image']);
                    if (file_exists($imagePath)) {
                        $newBrand->addMedia($imagePath)->preservingOriginal()->toMediaCollection(Brand::MEDIA_COLLECTION_NAME);
                    } else {
                        Log::warning("Image not found for category: " . $brandData['name']);
                    }
                }
            }
        }
        

        $taxes = [
            [ 'hsncode' => '600490', 'rate' => '5' ],
            [ 'hsncode' => '600510', 'rate' => '5' ],
            [ 'hsncode' => '600523', 'rate' => '5' ],
            [ 'hsncode' => '600631', 'rate' => '5' ],
            [ 'hsncode' => '600632', 'rate' => '5' ],
            [ 'hsncode' => '600690', 'rate' => '5' ],
            [ 'hsncode' => '610310', 'rate' => '5' ],
            [ 'hsncode' => '630210', 'rate' => '5' ],
            [ 'hsncode' => '680221', 'rate' => '5' ],
            [ 'hsncode' => '60051000', 'rate' => '5' ],
            [ 'hsncode' => '60052400', 'rate' => '5' ],
            [ 'hsncode' => '60063200', 'rate' => '5' ],
            [ 'hsncode' => '60069000', 'rate' => '5' ],
        ];

        foreach ($taxes as $tax) {
            Tax::create([
                'name' => $tax['hsncode'] . ' - GST ' . $tax['rate'] . '%',
                'hsncode' => $tax['hsncode'],
                'gstrate' => $tax['rate'],
            ]);
        }
    }
}
