<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProfilePhotoUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $profilePhotoUrl;

    public function __construct($userId, $profilePhotoUrl)
    {
        $this->userId = $userId;
        $this->profilePhotoUrl = $profilePhotoUrl;
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
            'profile_photo_url' => $this->profilePhotoUrl,
        ];
    }
}
