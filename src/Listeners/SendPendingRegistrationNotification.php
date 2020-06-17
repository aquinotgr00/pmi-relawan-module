<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\PendingVolunteerRegistration;
use BajakLautMalaka\PmiRelawan\Jobs\SendRegistrationStatus;

class SendPendingRegistrationNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  PendingVolunteerRegistration  $event
     * @return void
     */
    public function handle(PendingVolunteerRegistration $pendingRegistration)
    {
        dispatch(new SendRegistrationStatus($pendingRegistration->volunteer->load('user')));
    }
}
