<?php

namespace Fpaipl\Authy\Listeners;

use Fpaipl\Authy\Models\Account;
use Fpaipl\Authy\Models\Address;
use Fpaipl\Authy\Models\Profile;
use Illuminate\Auth\Events\Verified;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetupNewAuthyAccount implements ShouldQueue
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
    public function handle(Verified $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;
        $user->generateNewUserAccount($user);
    }
}
