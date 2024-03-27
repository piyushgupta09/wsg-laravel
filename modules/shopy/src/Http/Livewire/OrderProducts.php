<?php

namespace Fpaipl\Shopy\Http\Livewire;

use Livewire\Component;
use Fpaipl\Shopy\Models\Order;
use Fpaipl\Shopy\Event\OrderUpdateEvent;

class OrderProducts extends Component
{
    public $order;
    public $orderId;
    public $orderProducts;
    public $quantities = [];
    public $orderStatuses = [];

    public function mount($modelId){
        $this->orderId = $modelId;
        $this->order = Order::findOrFail($this->orderId);
        $this->orderProducts = $this->order->orderProducts;
        $this->orderStatuses = Order::STATUS;
        foreach ($this->orderProducts as $orderProduct) {
            foreach ($orderProduct->product->productOptions as $option) {
                foreach ($orderProduct->product->productRanges as $range) {
                    $this->quantities[$orderProduct->id][$option->id][$range->id] = $this->getOrderProductItemQuantity($orderProduct, $range, $option);
                }
            }
        }
    }

    public function getOrderProductItemQuantity($orderProduct, $range, $option)
    {
        $quantity = 0;
        foreach ($orderProduct->orderItems as $orderProductItem) {
            if ($orderProductItem->product_range_id == $range->id && $orderProductItem->product_option_id == $option->id) {
                $quantity = $orderProductItem->quantity;
            }
        }
        return $quantity;
    }

    public function getProductOptionsTotal($orderProduct, $option)
    {
        $total = 0;
        foreach ($orderProduct->orderItems as $orderProductItem) {
            if ($orderProductItem->product_option_id == $option->id) {
                $total += $orderProductItem->quantity;
            }
        }
        return $total;
    }

    public function getProductRangesTotal($orderProduct, $range)
    {
        $total = 0;
        foreach ($orderProduct->orderItems as $orderProductItem) {
            if ($orderProductItem->product_range_id == $range->id) {
                $total += $orderProductItem->quantity;
            }
        }
        return $total;
    }

    public function update($status)
    {
        $this->order->status = $status;
    
        try {
            $this->order->save();
            OrderUpdateEvent::dispatch($this->order);
            session()->flash('message', 'Order has been updated successfully.');
            return redirect(request()->header('Referer'));
        } catch (\Exception $e) {
            session()->flash('message', 'Some issue occurred.');
        }
    }    

    public function render()
    {
        return view('shopy::livewire.order-products');
    }
}
