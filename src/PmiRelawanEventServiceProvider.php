<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use BajakLautMalaka\PmiRelawan\Events\VolunteerRegistrationApproved;
use BajakLautMalaka\PmiRelawan\Events\ReportApproved;
use BajakLautMalaka\PmiRelawan\Events\ReportRejected;
use BajakLautMalaka\PmiRelawan\Events\ReportSubmitted;
use BajakLautMalaka\PmiRelawan\Listeners\SendApprovedVolunteerRegistrationNotification;
use BajakLautMalaka\PmiRelawan\Listeners\CreateAGroupEvent;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportApprovedMail;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportApprovedNotification;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportRejectedMail;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportRejectedNotification;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportSubmittedMail;
use BajakLautMalaka\PmiRelawan\Listeners\SendReportSubmittedNotification;

class PmiRelawanEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        VolunteerRegistrationApproved::class => [
            SendApprovedVolunteerRegistrationNotification::class,
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
