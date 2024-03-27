<?php

namespace Fpaipl\Authy\Traits;

use Fpaipl\Authy\Models\Address;

trait HasBillingAddress {

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }
}
