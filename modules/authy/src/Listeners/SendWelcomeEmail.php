<?php

namespace Fpaipl\Authy\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;
use Fpaipl\Authy\Mail\SendWelcomeMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail
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
        if ($user->usernameIsEmailId()) {
            Mail::to($event->user->email)->send(new SendWelcomeMail(
                $event->user->name
            ));
        }
        if ($user->usernameIsMobileNo()) {
            // Send welcome sms
        }
    }
}
