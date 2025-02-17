<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnonymChat\StoreUserParamsRequest;
use App\Events\SubscribeToChatEvent;
use App\Http\Requests\AnonymChat\MessageRequest;
use App\Http\Requests\AnonymChat\DisconnectChatRequest;
use App\Services\UserMatchingServices;
use App\Services\MessageServices;
use App\Services\DisconnectServices;


class AnonymChatController extends Controller
{
    public function index()
    {
        // инициализируем уникальный ид для чата.
        $userChatId = uniqid('chat_', true);
        event(new SubscribeToChatEvent($userChatId));
        return inertia('Chat', compact('userChatId'));
    }

    public function storeUserParams(StoreUserParamsRequest $request)
    {
        //получаем пользователя и его параметры.
        $data = $request->validated();
        return UserMatchingServices::userSearch($data);
    }

    public function message(MessageRequest $request)
    {
        $data = $request->validated();
        return MessageServices::message($data);
    }

    public function disconnect(DisconnectChatRequest $request)
    {
        $data = $request->validated();
        return DisconnectServices::disconnect($data);
    }
}
