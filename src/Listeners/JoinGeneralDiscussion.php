<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\EventParticipant;
use BajakLautMalaka\PmiRelawan\Events\VolunteerVerified;

class JoinGeneralDiscussion
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
     * @param  VolunteerVerified  $event
     * @return void
     */
    public function handle(VolunteerVerified $verified)
    {
        EventParticipant::create(['volunteer_id'=>$verified->volunteer->id,'event_report_id'=>1]);
    }
}
