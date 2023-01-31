<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DataSensorUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $value;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $value)
    {
        $this->type= $type;
        $this->value= $value;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('data-sensor-updated');
    }
}
