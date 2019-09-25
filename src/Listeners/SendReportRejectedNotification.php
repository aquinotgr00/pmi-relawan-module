<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\ReportRejected;
use Berkayk\OneSignal\OneSignalClient;

class SendReportRejectedNotification
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
     * @param  ReportRejected  $event
     * @return void
     */
    public function handle(ReportRejected $event)
    {
        $this->pushNotificationClient->sendNotificationToUser(
            'Mohon maaf, Laporan Darurat/Event "'.$event->report->title.'" belum bisa disetujui karena : '.$event->report->reason_rejection,
            $event->report->volunteer->user->device_id,
            null,
            ['rejection'=>['title'=>$event->report->title]]
        );
    }
}
