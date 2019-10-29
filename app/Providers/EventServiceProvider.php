<?php

namespace App\Providers;

use App\Models\Admin\AdminUser;
use App\Models\User;
use App\Observers\UidObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];
    public function boot():void
    {
        parent::boot();

        $this->registerUuidObservers();
    }

    public function registerUuidObservers():void
    {
        User::observe(app(UidObserver::class));

        AdminUser::observe(app(UidObserver::class));
    }
}
