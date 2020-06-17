<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\ReportApproved;
use Berkayk\OneSignal\OneSignalClient;

class SendReportApprovedNotification
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
     * @param  ReportApproved  $event
     * @return void
     */
    public function handle(ReportApproved $event)
    {
        $this->pushNotificationClient->sendNotificationToUser(
            'Laporan Darurat/Event "'.$event->report->title.'" telah disetujui. Selamat berpartisipasi!',
            $event->report->volunteer->user->device_id,
            null,
            ['approval'=>['title'=>$event->report->title]]
        );
    }
}
