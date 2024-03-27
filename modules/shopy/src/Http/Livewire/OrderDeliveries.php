<?php

namespace Fpaipl\Shopy\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Fpaipl\Shopy\Models\Order;
use Fpaipl\Shopy\Models\Delivery;
use Fpaipl\Shopy\Models\PickupAddress;
use Fpaipl\Shopy\Notifications\SendDeliveryNotification;

class OrderDeliveries extends Component
{
    use WithFileUploads;

    public $orderId;
    public $order;
    public $addresses;
    public $orderUser;
    public $orderDelivery;
    public $delivery_type;
    public $oldDelivery;
    public $newDelivery;
    public $mode;
    public $isCreateNewDelivery;

    public $image;
    public $error;

    public function mount($modelId)
    {
        $this->orderId = $modelId;
        $this->isCreateNewDelivery = true;

        $this->order = Order::findOrFail($this->orderId);
        $this->orderUser = $this->order->user;
        
        $this->orderDelivery = Delivery::where('order_id', $this->order->id)->where('status', '!=', Delivery::STATUS[3])->first();
        $this->image = $this->orderDelivery->getMediaFile();
       
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);
        $this->error = '';
        $this->image->storePublicly('temp', 'public');

    }

    public function deliveryCompleted($deliveryId)
    {
        if (!$this->image) {
            $this->error = 'Delivery Challan image is required, Upload before mark delivered.';
            return;
        }
        $delivery = Delivery::findOrFail($deliveryId);
        $delivery->status = Delivery::STATUS[2];
        $delivery->delivered_at = now();
        $delivery->save();
        $delivery->addMedia($this->image->getRealPath())->toMediaCollection(Delivery::MEDIA_COLLECTION_NAME);
        $this->image = null;
        $delivery->order->user->notify(new SendDeliveryNotification($delivery->order, $delivery));
        return redirect()->route('orders.show', $delivery->order->oid)->with('toast', [
            'class' => 'danger',
            'text' => 'Delivery marked as delivered.'
        ]);
    }

    public function render()
    {
        return view('shopy::livewire.order-deliveries');
    }
}
