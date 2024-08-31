<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NameUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $name;

    public function __construct($userId, $name)
    {
        $this->userId = $userId;
        $this->name = $name;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('global')
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'name' => $this->name,
        ];
    }
}
