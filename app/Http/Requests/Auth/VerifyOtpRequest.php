<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'string', 'size:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'otp.required' => 'Kode OTP wajib diisi',
            'otp.size' => 'Kode OTP harus 6 digit angka',
            'email.exists' => 'Email tidak terdaftar',
        ];
    }
}