<?php

namespace App\Events;

use App\Code;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReceivedCode implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $code;
    public $userID;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($c, $u)
    {
      $this->code = $c;
      $this->userID = $u;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel(env('PUSHER_CHANNEL_NAME'));
    }
}
