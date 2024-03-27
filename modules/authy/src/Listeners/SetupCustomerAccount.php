<?php

namespace Fpaipl\Authy\Listeners;

use Illuminate\Support\Str;
use Fpaipl\Shopy\Models\Cart;
use Fpaipl\Authy\Models\Address;
use Fpaipl\Authy\Events\Approved;
use Fpaipl\Shopy\Models\Checkout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Fpaipl\Panel\Notifications\WebPushNotification;
use Fpaipl\Shopy\Models\PickupAddress;

class SetupCustomerAccount implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Approved $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;
        $account = $user->account;
        
        $addressData = [
            'addressable_id' => $account->user_id,
            'addressable_type' => 'App\Models\User',    
            'gstin' => $account?->gstin,
            'pan' => $account?->pan,
            'name' => $account->name,
            'contacts' => $account->contact,
            'line1' => $account->address,
            'line2' => $account->city . ', ' . $account->state . ', India',
            'pincode' => $account->pincode,
        ];
    
        $addressModel = new Address($addressData);
        $account->user->addresses()->save($addressModel);

        $defaultCart = Cart::create([
            'name' => $user->name . ' Default Cart',
            'user_id' => $user->id,
        ]);

        $buynowCart = Cart::create([
            'name' => $user->name . ' Buynow Cart',
            'user_id' => $user->id,
        ]);
        
        $userCheckout = Checkout::create([
            'user_id' => $user->id,
            'delivery_type' => 'dropoff',
            'secret' => Str::random(6),
            'billing_shipping_same' => true,
            'billing_address_id' => $user->addresses[0]->id,
            'shipping_address_id' => $user->addresses[0]->id,
            'pickup_address_id' => PickupAddress::first()?->id,
            'pay_mode' => config('settings.pay_modes')[0]['id'],
        ]);

        $user->profile->update([
            'cart_default' => $defaultCart->id,
            'cart_buynow' => $buynowCart->id,
            'checkout' => $userCheckout->id,
            'billing' => $account->user->addresses[0]->id,
            'shipping' => $account->user->addresses[0]->id,
        ]);

        // send notification to user about account approval
        $user->notify(new WebPushNotification(
            'Account Approved',
            'Your account has been approved. You can now start shopping.',
            'https://wholesaleguruji.in/collections',
            '/logo.png'
        ));
    }
}
