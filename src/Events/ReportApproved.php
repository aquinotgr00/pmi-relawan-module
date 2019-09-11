<?php

namespace BajakLautMalaka\PmiRelawan\Events;

use BajakLautMalaka\PmiRelawan\EventReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ReportApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $report;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(EventReport $report)
    {
        $this->report = $report;
    }
}
