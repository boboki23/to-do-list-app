<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak sama',
            'password.min' => 'Password minimal 8 karakter',
            'password.letters' => 'Password harus mengandung huruf',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil',
            'password.numbers' => 'Password harus mengandung angka',
        ];
    }
}