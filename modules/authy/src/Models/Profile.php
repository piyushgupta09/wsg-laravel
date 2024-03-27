<?php

namespace Fpaipl\Authy\Models;

use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Panel\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use Authx, BelongsToUser;

    protected $fillable = [
        'user_id',
        'contacts',
        'tags',

        // 
        'role_assigned',
        'account',
        'cart_default',
        'cart_buynow',
        'checkout',
        'billing',
        'shipping',
    ];
}
