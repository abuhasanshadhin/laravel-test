<?php

namespace App\Events;

use stdClass;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PodcastProcessed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $syncInfo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $obj = new stdClass;
        $obj->message = 'Websocket test';
        $this->syncInfo = $obj;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('unit-sync');
    }
}
