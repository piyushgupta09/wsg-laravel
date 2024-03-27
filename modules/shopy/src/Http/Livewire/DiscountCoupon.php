<?php

namespace Fpaipl\Shopy\Http\Livewire;

use Livewire\Component;
use Fpaipl\Shopy\Models\Coupon;
use Fpaipl\Prody\Models\Product;

class DiscountCoupon extends Component
{
    public $couponId;
    public $coupon;

    public $product_id;
    public $couponProducts;
    public $allProducts;

    protected $listeners = [
        'add-search-select-selected' => 'handleSelectedProduct',
    ];

    public function mount($modelId)
    {
        $this->couponId = $modelId;
        $this->coupon = Coupon::findOrFail($this->couponId);
        $this->reloadData();
    }

    public function handleSelectedProduct($productId)
    {
        $this->product_id = $productId;
    }

    public function addProduct()
    {
        $product = Product::findOrFail($this->product_id);
        $couponProducts = json_decode($this->coupon->products, true);
        $couponProducts[] = $product->sid;
        $couponProducts = array_unique($couponProducts);
        $this->coupon->products = json_encode($couponProducts);
        $this->coupon->save();
        return redirect()->route('coupons.show', $this->coupon->id)->with('toast', [
            'class' => 'success',
            'text' => 'Product added successfully'
        ]);
    }

    public function removeProduct($productId)
    {
        $couponProducts = json_decode($this->coupon->products, true);
        $couponProducts = array_diff($couponProducts, [$productId]);
        $this->coupon->products = json_encode($couponProducts);
        $this->coupon->save();
        return redirect()->route('coupons.show', $this->coupon->id)->with('toast', [
            'class' => 'success',
            'text' => 'Product removed successfully'
        ]);
    }

    private function reloadData()
    {
        $this->couponProducts = Product::whereIn('sid', json_decode($this->coupon->products, true))->get();
        $this->allProducts = Product::all()->map(function ($product) {
            if ($this->couponProducts->contains('sid', $product->sid)) {
                return null;
            }
            return $product->productSelectData();
        })->filter();
    }    

    public function delete()
    {
        $this->coupon->delete();
        return redirect()->route('coupons.index')->with('toast', [
            'class' => 'success',
            'text' => 'Coupon deleted successfully'
        ]);
    }

    public function shuffleCode()
    {
        $this->coupon->code = strtoupper(substr(md5(uniqid()), 0, 8));
        $this->coupon->save();
        return redirect()->route('coupons.show', $this->coupon->id)->with('toast', [
            'class' => 'success',
            'text' => 'Coupon code shuffled successfully'
        ]);
    }

    public function activate()
    {
        $this->coupon->active = true;
        $this->coupon->save();
        return redirect()->route('coupons.show', $this->coupon->id)->with('toast', [
            'class' => 'success',
            'text' => 'Coupon activated successfully'
        ]);
    }

    public function deactivate()
    {
        $this->coupon->active = false;
        $this->coupon->save();
        return redirect()->route('coupons.show', $this->coupon->id)->with('toast', [
            'class' => 'success',
            'text' => 'Coupon deactivated successfully'
        ]);
    }

    public function render()
    {
        return view('shopy::livewire.discount-coupon');
    }
}
