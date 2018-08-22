<?php

namespace App\Events;

use App\RfidCode;

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
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($c)
    {
      $this->code = $c;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('code-channel');
    }
}
