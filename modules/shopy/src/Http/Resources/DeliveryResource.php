<?php

namespace Fpaipl\Shopy\Http\Resources;

use Illuminate\Http\Request;
use Fpaipl\Authy\Http\Resources\AddressResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!$this->name || $this->name == 'No Name') {
            $name = $this->order?->user?->name;
        } else {
            $name = $this->name;
        }
        if (!$this->contact || $this->contact == 'No Contact') {
            $contact = $this->order?->user?->account?->contact;
        } else {
            $contact = $this->contact;
        }

        return [
            'address' => $this->shipping_address,
            'type' => $this->type == 'pickup' ? 'Pickup' : 'Delivery',
            'name' => $name,
            'contact' => $contact,
            'secret' => $this->secret,
            'datetime' => $this->datetime,
            'note' => $this->note,
            'status' => $this->status,
            'challan' => $this->getMediaFile(),
            'shipped_at' => $this->shipped_at,
            'delivered_at' => $this->delivered_at,
            'rejected_at' => $this->rejected_at,
        ];
    }
}
