<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Events\ConnectionChatEvent;

class UserMatchingServices
{
    public static function userSearch(array $users)
    {
        $user = $users['user'];
        $parent = $users['parent'];
        $chatId = $users['userChatId'];

        //закидываем данные в redis
        $userData = [
            'chatId' => $chatId,
            'gender' => $user['gender'],
            'age' => $user['age'],
            'parent_age' => $parent['age'],
            'parent_gender' => $parent['gender']
        ];
        Redis::rpush('waiting', json_encode($userData));

        //получаем данные
        $list_users = Redis::lrange('waiting', 0, -1);

        //ищем собеседника для пользователя.
        foreach ($list_users as $users) {
            $users = json_decode($users, true);

            if (!is_array($users)) {
                continue; // Пропускаем, если декодирование не удалось
            }

            if ($users['chatId'] === $userData['chatId']) {
                continue;
            }

            if (
                $users['age'] === $userData['parent_age'] &&
                $users['gender'] === $users['parent_gender'] &&
                $users['parent_age'] === $userData['age'] &&
                $users['parent_gender'] === $userData['gender']
            ) {

                Redis::lrem('waiting', 0, json_encode($userData));
                Redis::lrem('waiting', 0, json_encode($users));

                event(new ConnectionChatEvent('соединение установленно', $users['chatId'], $userData['chatId']));
                event(new ConnectionChatEvent('соединение установленно', $userData['chatId'], $users['chatId']));
                
                return;
            }
        }

        return;
    }
}
