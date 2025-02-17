<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * уведомление пользоватя об отключении собеседника
 * 
 * @param $chatId ид чата для уведомления об отключении.
 * @param $disabled сообещние об отключении.
 */
class DisconnectChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $companionChatId;
    public $disabled;

    /**
     * Create a new event instance.
     */
    public function __construct($companionChatId, $disabled)
    {
        $this->companionChatId = $companionChatId;
        $this->disabled = $disabled;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel($this->companionChatId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'anonym-chat';
    }
}
