<?php

namespace Fpaipl\Shopy\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Fpaipl\Shopy\Http\Resources\CartProductResource;

class CheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'billing' => $this->billing_address_id,
            'same' => $this->billing_shipping_same,
            'shipping' => $this->shipping_address_id,
            'type' => $this->delivery_type,
            'name' => $this->name,
            'contact' => $this->contact,
            'secret' => $this->secret,
            'note' => $this->note,
            'datetime' => $this->datetime,
            'coupon' => $this->coupon,
            'coupon_value' => $this->coupon_value,
            'pay_mode' => $this->pay_mode,
            'pay_amt' => $this->pay_amt,
        ];
    }
}
