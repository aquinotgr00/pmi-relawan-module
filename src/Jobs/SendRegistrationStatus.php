<?php

namespace BajakLautMalaka\PmiRelawan\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use BajakLautMalaka\PmiRelawan\Mail\RegistrationStatus;
use BajakLautMalaka\PmiRelawan\Volunteer;
use Mail;

class SendRegistrationStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $email;
    
    protected $volunteer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email,Volunteer $volunteer)
    {
        $this->email    = $email;
        $this->volunteer   = $volunteer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         Mail::to($this->email)->send(
            new RegistrationStatus($this->volunteer)
        );
    }
}
