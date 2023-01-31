<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DataSensorUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $value;
    public $updated_at;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $value, $updated_at)
    {
        $this->type = $type;
        $this->value = $value;
        $date = Carbon::parse($updated_at)->locale('id');
        $date->settings(['formatFunction' => 'translatedFormat']);
        $this->updated_at =  $date->format('j F Y, H:i:s');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['data-sensor-updated'];
    }
}
