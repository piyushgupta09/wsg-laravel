<?php
  
namespace Fpaipl\Prody\Http\Livewire;
  
use Livewire\Component;
use Fpaipl\Prody\Models\Product;
use Fpaipl\Prody\Models\CollectionProduct;
  
class ProductDetails extends Component
{
    public $currentSection;
    public $sections;
    public $modelId;
    public $model;

    public $productOptions;
    public $productRanges;
    public $productAttributes;
    public $productMeasurements;
    public $productCollections;

    public function mount($modelId)
    {
        $this->modelId = $modelId;
        $this->model = Product::find($modelId);
        $this->currentSection = request()->section;

        $this->productOptions = $this->model->productOptions;
        $this->productRanges = $this->model->productRanges;
        $this->productAttributes = $this->model->productAttributes;
        $this->productMeasurements = $this->model->productMeasurements;
        $this->productCollections = $this->model->collections;
        
        $this->sections = collect([
            [
                'name' => 'Options',
                'slug' => 'options',
            ],
            [
                'name' => 'Ranges',
                'slug' => 'ranges',
            ],
            [
                'name' => 'Attributes',
                'slug' => 'attributes',
            ],
            [
                'name' => 'Measurements',
                'slug' => 'measurements',
            ],
            [
                'name' => 'Collections',
                'slug' => 'collections',
            ]
        ]);
    }

    public function getProductOption($collectionId)
    {
        return CollectionProduct::where('collection_id', $collectionId)->where('product_id', $this->modelId)->first()->productOption;
    }

    public function render()
    {
        return view('prody::livewire.product-details');
    }
}
