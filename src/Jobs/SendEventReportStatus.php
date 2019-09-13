<?php

namespace BajakLautMalaka\PmiRelawan\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use BajakLautMalaka\PmiRelawan\Mail\EventReportStatus;
use BajakLautMalaka\PmiRelawan\EventReport;
use Mail;

class SendEventReportStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $report;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(EventReport $report)
    {
        $this->report   = $report;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $report = $this->report;
        if (isset($report->volunteer->user->email)) {
            $email  = $report->volunteer->user->email;
            Mail::to($email)->send(
                new EventReportStatus($report)
            );
        }
    }
}
