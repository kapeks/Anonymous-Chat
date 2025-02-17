<?php

namespace App\Http\Requests\AnonymChat;

use Illuminate\Foundation\Http\FormRequest;

class DisconnectChatRequest extends FormRequest
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
            'companionChatId' => 'nullable|string|max:255',
            'userChatId' => 'nullable|string|max:255',
        ];
    }

    protected function prepareForValidation()
    {
        if (empty($this->input('companionChatId')) && empty($this->input('userChatId'))) {
            $this->merge(['companionChatId' => null, 'userChatId' => null]);
        }
    }
}
