<?php

namespace Fpaipl\Prody\Actions;

use Fpaipl\Prody\Models\Tax;
use Fpaipl\Prody\Models\Brand;
use Fpaipl\Prody\Models\Product;
use Fpaipl\Panel\Services\Syncme;
use Fpaipl\Prody\Models\Category;
use Illuminate\Support\Facades\Log;
use Fpaipl\Prody\Models\ProductRange;
use Fpaipl\Prody\Models\ProductOption;
use Fpaipl\Prody\Models\ProductAttribute;
use Fpaipl\Prody\Models\CollectionProduct;
use Fpaipl\Prody\Models\ProductMeasurement;

class LoadProducts
{
    public static function execute($wsgBrandId, $debug = false): int
    {
        $debug = false;
        
        if ($debug) {
            Log::info('LoadProductsAction execute start');
        }

        try {

            $apiRoute = config('wsgbrand.api.sync.products') . '/' . $wsgBrandId;
            $response = (new Syncme(true, config('wsgbrand')))->post($apiRoute);

            if (!isset($response['data']) || $response['status'] == 'error') {
                throw new \Exception(isset($response['message']) ? $response['message'] : 'Unknown error');
            }

            if ($debug) {
                Log::info('LoadProductsAction response: ', (array)$response);
            }

            $count = self::saveData($response, $debug);
            if ($debug) {
                Log::info('Total new products created: ' . $count);
            }

            return $count;

        } catch (\Exception $e) {
            if ($debug) {
                Log::error('LoadProductsAction error: ', ['error' => $e->getMessage()]);
            }
            return 0;
        }
    }

    private static function saveData(array $data, bool $debug = false): int
    {
        $count = 0;

        // Determine the structure of the data
        $datalist = is_string($data['data']) ? json_decode($data['data'], true) : $data['data'];

        // Debug log to ensure data structure is as expected.
        if ($debug) {
            Log::info('LoadProductsAction data structure: ', $datalist);
        }
        
        // Now if array is not empty, loop through the data and save it to the database.
        if (!empty($datalist)) {

            if ($debug) {
                Log::info('LoadProductsAction data count: ' . count($datalist));
            }

            foreach ($datalist as $product) {

                if ($product['brand']) {
                    $productBrandId = Brand::find($product['brand'])?->id;
                    if (!$productBrandId) {
                        if ($debug) {
                            Log::error('LoadProductsAction error: ', ['error' => 'Brand not found']);
                        }
                        continue;
                    }
                }
                if ($product['category']) {
                    $productCategoryId = Category::find($product['category'])?->id;
                    if (!$productCategoryId) {
                        if ($debug) {
                            Log::error('LoadProductsAction error: ', ['error' => 'Category not found']);
                        }
                        continue;
                    }
                }
                if ($product['tax']) {
                    $productTaxId = Tax::find($product['tax'])?->id;
                    if (!$productTaxId) {
                        if ($debug) {
                            Log::error('LoadProductsAction error: ', ['error' => 'Tax not found']);
                        }
                        continue;
                    }
                }

                $newProduct = Product::updateOrCreate(
                    [
                        'sid' => $product['sid'],
                        'uuid' => $product['uuid'],
                    ],
                    [
                        'name' => $product['name'],
                        'slug' => $product['slug'],
                        'code' => $product['code'],
                        'details' => $product['details'],
                        'mrp' => $product['mrp'],
                        'rate' => $product['rate'],
                        'moq' => $product['moq'],
                        'active' => $product['active'],
                        'tags' => $product['tags'],
                        'brand_id' => $productBrandId,
                        'category_id' => $productCategoryId,
                        'tax_id' => $productTaxId,
                        'in_stock' => $product['in_stock'],
                        'stocks' => json_encode($product['stocks']),
                    ]
                );

                if ($debug) {
                    Log::info(json_encode($newProduct));
                }
    
                if (isset($product['product_options']) && is_array($product['product_options'])) {
                    foreach ($product['product_options'] as $productOption) {
                        ProductOption::updateOrCreate(
                            [
                                'product_id' => $newProduct->id,
                                'slug' => $productOption['slug'],
                            ],
                            [
                                'name' => $productOption['name'],
                                'code' => $productOption['code'],
                                'image' => json_encode($productOption['image']),
                                'images' => json_encode($productOption['images']),
                                'active' => $productOption['active'],
                            ]
                        );
                    }
                }
    
                if (isset($product['product_ranges']) && is_array($product['product_ranges'])) {
                    foreach ($product['product_ranges'] as $productRange) {
                        ProductRange::updateOrCreate(
                            [
                                'product_id' => $newProduct->id,
                                'slug' => $productRange['slug'],
                            ],
                            [
                                'name' => $productRange['name'],
                                'mrp' => $productRange['mrp'],
                                'rate' => $productRange['rate'],
                                'active' => $productRange['active'],
                            ]
                        );
                    }
                }
    
                if (isset($product['product_attributes']) && is_array($product['product_attributes'])) {
                    foreach ($product['product_attributes'] as $productAttribute) {
                        ProductAttribute::updateOrCreate(
                            [
                                'product_id' => $newProduct->id,
                                'name' => $productAttribute['name'],
                            ],
                            [
                                'value' => $productAttribute['value'],
                            ]
                        );
                    }
                }
    
                if (isset($product['product_measurements']) && is_array($product['product_measurements'])) {
                    foreach ($product['product_measurements'] as $productMeasurement) {
                        ProductMeasurement::updateOrCreate(
                            [
                                'product_id' => $newProduct->id,
                                'name' => $productMeasurement['name'],
                                'product_range_slug' => $productMeasurement['product_range_slug'],
                            ],
                            [
                                'size' => $productMeasurement['size'],
                                'unit' => $productMeasurement['unit'],
                                'value' => $productMeasurement['value'],
                            ]
                        );
                    }
                }

                if (isset($product['product_collections']) && is_array($product['product_collections'])) {
                    // delete all existing collection products
                    // CollectionProduct::where('product_id', $newProduct->id)->delete();
                    foreach ($product['product_collections'] as $productCollections) {
                        if ($debug) {
                            Log::info('CollectionProduct: ', $productCollections);
                        }
                        CollectionProduct::updateOrCreate(
                            [
                                'product_id' => $newProduct->id,
                                'collection_id' => $productCollections['wsg_collection_id'],
                            ],
                            [
                                'product_option_id' => $productCollections['product_option_id'],
                                // 'position' => $productCollections['position'],
                            ]
                        );
                    }
                }

                if ($newProduct->wasRecentlyCreated) {
                    $count++;
                    $newProduct->addToRangedCollection();
                }
            }
        }

        return $count;
    }
}
