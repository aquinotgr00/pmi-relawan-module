<?php

namespace BajakLautMalaka\PmiRelawan\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use BajakLautMalaka\PmiRelawan\Volunteer;

class RegistrationStatus extends Mailable
{
    use Queueable, SerializesModels;

    protected $volunteer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Volunteer $volunteer)
    {
        $this->volunteer = $volunteer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view       = 'volunteer::emails.mail-registrasi-relawan';
        $volunteer  = $this->volunteer;
        $subject    = '[PENDING] Registrasi Relawan';
        if (isset($volunteer->verified)) {
            if ($volunteer->verified === 1) {
                $subject    ='[APPROVED] Registrasi Relawan';
                $view       ='volunteer::emails.mail-registrasi-relawan-berhasil';
            }
            if ($volunteer->trashed()) {
                $subject    ='[REJECTED] Registrasi Relawan';
                $view       ='volunteer::emails.mail-registrasi-relawan-reject';
            }
        }
        return $this->subject($subject)->view($view,compact('volunteer'));
    }
}
