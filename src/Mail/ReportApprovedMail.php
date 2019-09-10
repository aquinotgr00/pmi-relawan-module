<?php

namespace BajakLautMalaka\PmiRelawan\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use BajakLautMalaka\PmiRelawan\EventReport;

class ReportApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $report;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EventReport $report)
    {
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $report     = $this->report;
        $subject    = '[DISETUJUI] '.$report->title;
        $view       = 'volunteer::emails.mail-lapor-berhasil';
        return $this->subject($subject)->view($view,compact('report'));
    }
}
