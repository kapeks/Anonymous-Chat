<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


/**
 * подписка на свой индивидуальный канал.
 * 
 * @param userChatId уникальный канал.
 */
class SubscribeToChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userChatId;
    // public $connection;

    /**
     * Create a new event instance.
     */
    public function __construct($userChatId)
    {
        $this->userChatId = $userChatId;
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
