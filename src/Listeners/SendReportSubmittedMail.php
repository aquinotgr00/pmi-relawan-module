<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\ReportSubmitted;
use BajakLautMalaka\PmiRelawan\Mail\ReportSubmittedMail;

class SendReportSubmittedMail
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
     * @param  ReportSubmitted  $event
     * @return void
     */
    public function handle(ReportSubmitted $event)
    {
        if (isset($event->report->volunteer->user->email)) {
            $email  = $event->report->volunteer->user->email;
            \Mail::to($email)->send(
                new ReportSubmittedMail($event->report)
            );
        }
    }
}
