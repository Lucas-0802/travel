<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Autoriza a requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define as regras de validação.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ];
    }

    /**
     * Define as mensagens de erro personalizadas.
     */
    public function messages(): array
    {
        return [
            'name.required' => ('name_required'),
            'email.required' => ('email_required'),
            'email.email' => ('email_invalid'),
            'email.unique' => ('email_taken'),
            'password.required' => ('password_required'),
            'password.confirmed' => ('password_mismatch'),
        ];
    }
}
