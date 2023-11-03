<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class AlarmRead
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $alarm;

    /**
     * update alarm read
     *
     * @param  \App\Models\Alarm  $alarm
     * @return void
     */
    public function __construct($alarm)
    {
        $this->alarm = $alarm;
    }

    public function broadcastOn() {
        return new PrivateChannel('channel-alarm');
    }
}
