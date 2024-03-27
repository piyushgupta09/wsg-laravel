<?php

namespace Fpaipl\Shopy\Models;

use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Shopy\Models\Delivery;
use Illuminate\Database\Eloquent\Model;

class PickupAddress extends Model
{
    use Authx;

    protected $fillable = [
        'print',
        'name',
        'lname',
        'contacts',
        'line1',
        'line2',
        'district',
        'state',
        'country',
        'pincode',
    ];
    
}

