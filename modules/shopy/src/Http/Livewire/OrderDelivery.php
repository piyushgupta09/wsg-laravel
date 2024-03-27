<?php

namespace Fpaipl\Shopy\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Fpaipl\Shopy\Models\Delivery;
use Fpaipl\Shopy\Notifications\SendDeliveryNotification;

class OrderDelivery extends Component
{
    use WithFileUploads;

    public $image;
    public $error;
    public $orderDelivery;

    public function mount(Delivery $modelId)
    {
        $this->orderDelivery = $modelId;
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
        return redirect()->route('deliveries.show', $delivery->id)->with('toast', [
            'class' => 'danger',
            'text' => 'Delivery marked as delivered.'
        ]);
    }

    public function render()
    {
        return view('shopy::livewire.order-delivery');
    }
}
