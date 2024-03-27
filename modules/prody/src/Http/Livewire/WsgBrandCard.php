<?php

namespace Fpaipl\Prody\Http\Livewire;

use Livewire\Component;
use Fpaipl\Prody\Models\Product;
use Fpaipl\Panel\Services\Syncme;
use Fpaipl\Prody\Models\WsgBrand;
use Illuminate\Support\Facades\DB;
use Fpaipl\Prody\Models\Collection;
use Illuminate\Support\Facades\Log;
use Fpaipl\Prody\Actions\LoadProducts;

class WsgBrandCard extends Component
{
    public $wsgBrand;
    public $counts;
    public $loading = true;

    public function mount($modelId)
    {
        $this->wsgBrand = WsgBrand::where('uuid', $modelId)->first();
        $this->updateCount();
    }

    public function updateCount()
    {
        $this->loading = true;

        // Old Counts
        $this->counts = [
            'products' => [
                'old' => Product::count(),
                'new' => 0
            ],
        ];

        // New Counts
        $apiRoute = config('wsgbrand.api.sync.products_count') . '/' . $this->wsgBrand->uuid;
        $response = (new Syncme(true, config('wsgbrand')))->post($apiRoute);
        $this->loading = false;
        if ($response['status'] !== 'success') {
            return [];
        }
        $this->counts['products']['new'] = $response['data']['products'];
    }

    public function downloadProducts()
    {
        $this->loading = true;
        $count = LoadProducts::execute($this->wsgBrand->uuid, false);
        $message = $count > 0 ? $count . ' products synced' : 'No new products found';
        return redirect()->route('wsg-brands.index')->with('toast', [
            'class' => 'success',
            'text' => $message
        ]);
    }

    public function saveCollections($data)
    {
        if (isset($data['collections'])) {
            foreach ($data['collections'] as $collectionData) {
                // Create or update the collection itself
                $collection = Collection::updateOrCreate(
                    ['id' => $collectionData['id']],
                    [
                        'name' => $collectionData['name'],
                        'slug' => $collectionData['slug'],
                        'type' => $collectionData['type'],
                        'active' => $collectionData['active'],
                        'order' => $collectionData['order'],
                        'tags' => $collectionData['tags'],
                        'info' => $collectionData['info'],
                    ]
                );
    
                // Handle the associated products for the collection
                if (isset($collectionData['collection_products_table'])) {
                    // First, detach any existing products to prevent duplicates
                    $collection->products()->detach();
    
                    // Now, attach the new products with their additional pivot data
                    foreach ($collectionData['collection_products_table'] as $collectionProductData) {
                        $productExists = Product::where('id', $collectionProductData['product_id'])->exists();
                        if (!$productExists) {
                            Log::error("Product ID {$collectionProductData['product_id']} does not exist.");
                            // Handle the error, e.g., skip this iteration, log a detailed error, etc.
                            continue;
                        }
                        // Attach the product to the collection with the additional pivot data
                        $collection->products()->attach($collectionProductData['product_id'], [
                            'product_option_id' => $collectionProductData['product_option_id'],
                            // 'created_at' and 'updated_at' can be set to current time if necessary
                            'created_at' => now(), // or use $collectionProductData['created_at'] if not null
                            'updated_at' => now(), // or use $collectionProductData['updated_at'] if not null
                        ]);
                    }
                }
            }
        }
        Log::info('Collections saved');
    }

    public function render()
    {
        return view('prody::livewire.wsg-brand-card');
    }
}
