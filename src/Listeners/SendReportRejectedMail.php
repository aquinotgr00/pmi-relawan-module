<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\ReportRejected;
use BajakLautMalaka\PmiRelawan\Mail\ReportRejectedMail;

class SendReportRejectedMail
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
     * @param  ReportRejected  $event
     * @return void
     */
    public function handle(ReportRejected $event)
    {
        if (isset($event->report->volunteer->user->email)) {
            $email  = $event->report->volunteer->user->email;
            \Mail::to($email)->send(
                new ReportRejectedMail($event->report)
            );
        }
    }
}
