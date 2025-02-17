<?php

namespace App\Http\Requests\AnonymChat;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string',
            'userChatId' => 'required|string',
            'companionChatId' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'введите ваше сообщение',
            'body.string' => 'сообщение должно быть ввиде строки',
            'userChatId.string' => 'ошибка подключения',
            'userChatId.required' => 'ошибка подключения',
            'companionChatId.required' => 'ошибка подключения',
            'companionChatId.string' => 'ошибка подключения'
        ];
    }
}
