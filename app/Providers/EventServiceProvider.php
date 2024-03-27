<?php

namespace App\Providers;

use Fpaipl\Authy\Events\Approved;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;
use Fpaipl\Authy\Listeners\SendWelcomeEmail;
use Fpaipl\Authy\Listeners\SetupCustomerAccount;
use Fpaipl\Authy\Listeners\SetupNewAuthyAccount;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Verified::class => [
            SendWelcomeEmail::class,
            SetupNewAuthyAccount::class,
        ],
        Approved::class => [
            SetupCustomerAccount::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
