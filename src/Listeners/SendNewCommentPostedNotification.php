<?php

namespace BajakLautMalaka\PmiRelawan\Listeners;

use BajakLautMalaka\PmiRelawan\Events\CommentPosted;
use Berkayk\OneSignal\OneSignalClient;

class SendNewCommentPostedNotification
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
     * @param  CommentPosted  $event
     * @return void
     */
    public function handle(CommentPosted $commentPosted)
    {
        $this->pushNotificationClient->sendNotificationToSegment(
            $commentPosted->user->name.' mengirimkan sesuatu di '.$commentPosted->comment->event->title,
            'Volunteers',
            null,
            ['rsvpId'=>$commentPosted->comment->event_report_id]
        );
    }
}
