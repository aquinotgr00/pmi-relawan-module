<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\ReportSubmitted;
use Berkayk\OneSignal\OneSignalClient;

class SendReportSubmittedNotification
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
        // TODO : Notification for admin dashboard
    }
}
