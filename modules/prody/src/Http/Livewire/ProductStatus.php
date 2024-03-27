<?php

namespace Fpaipl\Prody\Http\Livewire;

use Fpaipl\Prody\Models\Product;
use Livewire\Component;

/**
 * This Livewire component handles product material
 */
class ProductStatus extends Component
{
    public $productStatus;
    public $product;

    /**
     * Function that runs when the component is initialized
     */
    public function mount($modelId)
    {
        $this->productStatus = Product::STATUS;
        $this->product = Product::find($modelId);
    }

    public function updateStatus($status)
    {
        $this->product->status = $status;
        $this->product->save();
        if ($status == Product::STATUS[1]) {
            $this->product->addToRangedCollection();
            $this->product->addToRecommendedCollection();
        } else {
            $this->product->removeFromRangedCollection();
            $this->product->removeFromRecommendedCollection();
        }
        session()->flash('message', 'Product status updated successfully.');
    }

    public function render()
    {
        return view('prody::livewire.product-status');
    }
}
