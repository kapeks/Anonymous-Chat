<?php

namespace App\Http\Requests\AnonymChat;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserParamsRequest extends FormRequest
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
            'user.age' => 'required|string|in:under_16,17_22,23_27,28_35,36_plus',
            'user.gender' => 'required|string|in:male,female',
            'parent.age' => 'required|string|in:under_16,17_22,23_27,28_35,36_plus',
            'parent.gender' => 'required|string|in:male,female',
            'userChatId' => 'string|required'
        ];
    }

    public function messages(): array
    {
        return [
            'parent.age.required' => 'Возраст собеседника обязателен.',
            'parent.age.in' => 'Выберите допустимое значение возраста.',
            'parent.age.string' => 'только строка.',
            'parent.gender.required' => 'выберите пол собеседника.',
            'parent.gender.in' => 'выберите допустимое значение пола.',
            'user.age.required' => 'укажите ваш возраст.',
            'user.age.in' => 'Выберите допустимое значение возраста.',
            'user.age.string' => 'только строка.',
            'user.gender.required' => 'выберите ваш пол.',
            'user.gender.in' => 'выберите допустимое значение пола.',
            'userChatId.required' => 'произошла ошибка поиска',
            'userChatId.string' => 'произошла ошибка поиска'
        ];
    }
}
