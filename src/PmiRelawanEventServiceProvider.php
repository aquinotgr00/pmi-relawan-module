<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use BajakLautMalaka\PmiRelawan\Events\VolunteerRegistrationApproved;
use BajakLautMalaka\PmiRelawan\Listeners\SendApprovedVolunteerRegistrationNotification;

class PmiRelawanEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        VolunteerRegistrationApproved::class => [
            SendApprovedVolunteerRegistrationNotification::class,
        ],
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
