<?php

namespace BajakLautMalaka\PmiRelawan\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use BajakLautMalaka\PmiRelawan\EventReport;

class EventReportStatus extends Mailable
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
        $view       = 'volunteer::emails.mail-lapor';
        $report     = $this->report;
        $subject    = '[PENDING] Laporan ';
        if (isset($report->approved)) {
            if ($report->approved === 1) {
                $subject    ='[APPROVED] Laporan';
                $view       ='volunteer::emails.mail-lapor-berhasil';
            }
            if ($report->approved === 0) {
                $subject    ='[REJECTED] Laporan';
                $view       ='volunteer::emails.mail-lapor-ditolak';
            }
        }
        if (isset($report->title)) {
            $subject.= $report->title;
        }
        return $this->subject($subject)->view($view,compact('report'));
    }
}
