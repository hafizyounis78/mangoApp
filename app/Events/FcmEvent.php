<?php


namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FcmEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $token;
    public $title;
    public $data;

    public function __construct($token, $title, $data)
    {
        $this->token = $token;
        $this->title = $title;
        $this->data = $data;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
