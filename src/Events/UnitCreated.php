<?php

namespace BajakLautMalaka\PmiRelawan\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\User;
use BajakLautMalaka\PmiRelawan\UnitVolunteer;

class UnitCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    public $unit;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UnitVolunteer $unit)
    {
        //
        $this->unit = $unit;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('unit');
    }

    public function broadcastAs()
    {
        return 'settings.unit';
    }
}
