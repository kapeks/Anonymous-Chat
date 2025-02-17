<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class ConnectionChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $connected;
    public $userChatId;
    public $companionChatId;

    /**
     * Create a new event instance.
     */
    public function __construct($connected, $userChatId, $companionChatId)
    {
        $this->userChatId = $userChatId;
        $this->connected = $connected;
        $this->companionChatId = $companionChatId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel($this->userChatId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'anonym-chat';
    }
}
