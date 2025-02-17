<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use App\Events\DisconnectChatEvent;

class DisconnectServices
{
    public static function disconnect(array $data)
    {
        if (isset($data['userChatId'])) {
            log::info('событие disconnect запущено!');
            $userChatId = $data['userChatId'];
            $waitingList = Redis::lrange('waiting', 0, -1); 

            foreach ($waitingList as $item) {
                $data = json_decode($item, true);
                if ($data && $data['chatId'] === $userChatId) {
                    Redis::lrem('waiting', 0, $item);
                    Log::info('пользователь удален: ' . $userChatId);
                    break;
                }
            }
        } elseif (isset($data['companionChatId'])) {
            Log::info('Пользователь отключился: ' . $data['companionChatId']);
            event(new DisconnectChatEvent($data['companionChatId'], 'пользователь отключился'));
        } else {
            Log::warning('Некорректные данные для отключения');
        }
    }
}
