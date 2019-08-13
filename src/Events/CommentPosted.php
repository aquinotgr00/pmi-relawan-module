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
use BajakLautMalaka\PmiRelawan\EventActivity;

class CommentPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, EventActivity $comment)
    {
        //
        $this->user = $user;
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('event.'.$this->comment->event_report_id);
    }

    public function broadcastAs()
    {
        return 'event.comment';
    }
}
