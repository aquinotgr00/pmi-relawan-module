<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use BajakLautMalaka\PmiRelawan\Events\PendingVolunteerRegistration;
use BajakLautMalaka\PmiRelawan\Events\VolunteerVerified;
use BajakLautMalaka\PmiRelawan\Events\VolunteerVerificationCompleted;
use BajakLautMalaka\PmiRelawan\Events\ReportApproved;
use BajakLautMalaka\PmiRelawan\Events\ReportRejected;
use BajakLautMalaka\PmiRelawan\Events\ReportSubmitted;
use BajakLautMalaka\PmiRelawan\Events\CommentPosted;
use BajakLautMalaka\PmiRelawan\Listeners\SendPendingRegistrationNotification;
use BajakLautMalaka\PmiRelawan\Listeners\SendRegistrationCompleteNotification;
use BajakLautMalaka\PmiRelawan\Listeners\JoinGeneralDiscussion;
use BajakLautMalaka\PmiRelawan\Listeners\CreateAGroupEvent;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportApprovedMail;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportApprovedNotification;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportRejectedMail;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportRejectedNotification;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportSubmittedMail;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportSubmittedNotification;
use BajakLautMalaka\PmiRelawan\Listeners\SendNewCommentPostedNotification;

class PmiRelawanEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PendingVolunteerRegistration::class=>[
            // TODO : send new volunteer registration notification to admin
            // NotifyAdmin::class,
            SendPendingRegistrationNotification::class
        ],
        VolunteerVerified::class => [
            JoinGeneralDiscussion::class,
        ],
        VolunteerVerificationCompleted::class => [
            SendRegistrationCompleteNotification::class
        ],
        ReportApproved::class => [
            CreateAGroupEvent::class,
            SendReportApprovedMail::class,
            SendReportApprovedNotification::class,
        ],
        ReportRejected::class => [
            SendReportRejectedMail::class,
            SendReportRejectedNotification::class,
        ],
        ReportSubmitted::class => [
            SendReportSubmittedMail::class,
            SendReportSubmittedNotification::class
        ],
        CommentPosted::class => [
            SendNewCommentPostedNotification::class,
        ]
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
