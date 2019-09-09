<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\ReportApproved;
use Berkayk\OneSignal\OneSignalClient;

class SendReportApprovedNotification
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
        $pushNotificationAppId      = config('donation.push_notification.app_id',env('ONESIGNAL_APP_ID'));
        $pushNotificationRestApiKey = config('donation.push_notification.rest_api_key',env('ONESIGNAL_REST_API_KEY'));
        $pushNotificationClient     = new OneSignalClient($pushNotificationAppId, $pushNotificationRestApiKey, $pushNotificationRestApiKey);
        if (isset($event->report->volunteer->user->id)) {
            $userId = $event->report->volunteer->user->id;
            $pushNotificationClient->sendNotificationToUser($event->report->title, $userId,null, null, null, null);
        }
    }
}
