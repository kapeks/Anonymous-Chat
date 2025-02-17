<?php

namespace App\Services;

use App\Events\AnonymChatEvent;

class MessageServices
{
    public static function message(array $data)
    {
        $companionChatId = $data['companionChatId'];
        $message = $data['body'];
        $userChatId = $data['userChatId'];

        broadcast(new AnonymChatEvent($message, $userChatId, $companionChatId))->toOthers();
        broadcast(new AnonymChatEvent($message, $companionChatId, $userChatId))->toOthers();

        return response()->json(['myMessage' => $message]);
    }
}