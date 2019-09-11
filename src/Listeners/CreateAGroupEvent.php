<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\ReportApproved;
use BajakLautMalaka\PmiRelawan\EventParticipant;

class CreateAGroupEvent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ReportApproved  $event
     * @return void
     */
    public function handle(ReportApproved $event)
    {
        //add partisipant author
        $event->report->participants()->saveMany([
            new EventParticipant([
                'event_report_id' => $event->report->id,
                'volunteer_id' => $event->report->volunteer_id,
            ])
        ]);
    }
}
