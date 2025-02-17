<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnonymChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $userChatId;
    public $companionChatId;


    /**
     * Create a new event instance.
     */
    public function __construct($message, $userChatId, $companionChatId)
    {
        $this->message = $message;
        $this->userChatId = $userChatId;
        $this->companionChatId = $companionChatId;
        Log::info("Передача события с chatId: $userChatId, companionChatId: $companionChatId");
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

    public function broadcastWith() 
    {
        return [
            'message' => $this->message,
            'userChatId' => $this->userChatId,
            'companionChatId' => $this->companionChatId
        ];
    }
}
