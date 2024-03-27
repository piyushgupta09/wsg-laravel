<?php

namespace Fpaipl\Authy\Traits;

use Fpaipl\Authy\Models\Address;

trait HasShippingAddress {

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }
}
