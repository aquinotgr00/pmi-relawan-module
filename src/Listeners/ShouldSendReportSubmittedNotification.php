<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\ReportSubmitted;
use Berkayk\OneSignal\OneSignalClient;

class ShouldSendReportSubmittedNotification
{
    private $pushNotificationClient;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OneSignalClient $pushNotificationClient)
    {
        $this->pushNotificationClient = $pushNotificationClient;
    }

    /**
     * Handle the event.
     *
     * @param  ReportSubmitted  $event
     * @return void
     */
    public function handle(ReportSubmitted $submitted)
    {
        if($submitted->report->admin_id) {
            $this->pushNotificationClient->sendNotificationToSegment(
                'Hai sahabat PMI, ada kegiatan baru yang diinisiasi Admin!',
                'Volunteers',
                null,
                null
            );
        }
        else {
            // TODO : Notification for admin dashboard
        }

    }
}
