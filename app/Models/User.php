<?php

namespace App\Models;

use Fpaipl\Shopy\Models\Cart;
use Fpaipl\Shopy\Models\Order;
use Fpaipl\Authy\Models\Account;
use Fpaipl\Authy\Models\Profile;
use Fpaipl\Prody\Models\Product;
use Fpaipl\Shopy\Models\Checkout;
use Fpaipl\Authy\Models\User as AuthyUser;

class User extends AuthyUser {

    public function favourites()
    {
        return $this->belongsToMany(Product::class, 'product_users', 'user_id', 'product_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function checkout()
    {
        return $this->hasOne(Checkout::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function my_shop_products()
    {
        return $this->orders
            ->filter(function($order) {
                return $order->status == 'completed';
            })
            ->flatMap(function ($order) {
                return $order->orderProducts->map(function ($orderProduct) {
                    return $orderProduct->product;
                });
            })
            ->unique('id');
    }   
   
    public function generateNewUserAccount($user)
    {
        $user->utype = $user->usernameIsEmailId() ? 'email' : 'mobile';
        $user->saveQuietly();

        $user->assignRole('user');

        $userAccount = Account::create([
            'user_id' => $user->id,
            'kycstep' => 'business',
            'name' => $user->name,

        ]);

        Profile::create([
            'user_id' => $user->id,
            'role_assigned' => true,
            'account' => $userAccount->id,
        ]);
    }

}
