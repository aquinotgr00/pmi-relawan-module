<?php

namespace BajakLautMalaka\PmiRelawan\Events;

use BajakLautMalaka\PmiRelawan\Volunteer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class VolunteerVerified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $volunteer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Volunteer $volunteer)
    {
        $this->volunteer = $volunteer;
    }
}
