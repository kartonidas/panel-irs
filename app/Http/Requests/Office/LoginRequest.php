<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email" => ["required", "email"],
            "password" => ["required"],
        ];
    }
    
    public function messages(): array
    {
        return [
            "email.required" => __("Uzupełnij adres e-mail"),
            "email.email" => __("Nieprawidłowy adres e-mail"),
            'password.required' => __("Uzupełnij hasło"),
        ];
    }
}