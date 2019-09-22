<?php

namespace BajakLautMalaka\PmiRelawan\Events;

use BajakLautMalaka\PmiRelawan\EventParticipant;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PendingParticipant
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $eventParticipant;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(EventParticipant $eventParticipant)
    {
        $this->eventParticipant = $eventParticipant;
    }
}
