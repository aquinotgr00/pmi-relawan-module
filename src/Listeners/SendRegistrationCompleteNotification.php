<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\VolunteerVerificationCompleted;
use BajakLautMalaka\PmiRelawan\Jobs\SendRegistrationStatus;

class SendRegistrationCompleteNotification
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
     * @param  VolunteerVerificationCompleted  $event
     * @return void
     */
    public function handle(VolunteerVerificationCompleted $verified)
    {
        dispatch(new SendRegistrationStatus($verified->volunteer->load('user')));
    }
}
