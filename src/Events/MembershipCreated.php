<?php

namespace BajakLautMalaka\PmiRelawan\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use BajakLautMalaka\PmiRelawan\Membership;

class MembershipCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    public $membership;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Membership $membership)
    {
        //
        $this->membership = $membership;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('membership');
    }

    public function broadcastAs()
    {
        return 'settings.membership';
    }
}
