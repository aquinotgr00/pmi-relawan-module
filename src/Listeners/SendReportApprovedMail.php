<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\ReportApproved;
use BajakLautMalaka\PmiRelawan\Mail\ReportApprovedMail;

class SendReportApprovedMail
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
        if (isset($event->report->volunteer->user->email)) {
            $email  = $event->report->volunteer->user->email;
            \Mail::to($email)->send(
                new ReportApprovedMail($event->report)
            );
        }
    }
}
