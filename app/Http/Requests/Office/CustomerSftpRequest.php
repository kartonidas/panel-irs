<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerSftpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "host" => ["sometimes", "max:200"],
            "port" => ["nullable", "integer"],
            "login" => ["sometimes", "max:150"],
            "set_password" => ["nullable", "boolean"],
            "password" => ["sometimes", "max:200"],
            "path" => ["sometimes", "max:150"],
            "transfer_type" => ["sometimes", Rule::in(["active", "passive"])],
            "ssl" => ["sometimes", "boolean"],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "host.max" => __("Maksymalna długość w polu adres serwera to : max znaków"),
            "port.integer" => __("Nieprawidłowa wartość w polu port (tylko liczby całkowite)"),
            "login.max" => __("Maksymalna długość w polu login to :max znaków"),
            "password.max" => __("Maksymalna długość w polu hasło to :max znaków"),
            "path.max" => __("Maksymalna długość w polu ścieżka to :max znaków"),
            "transfer_type.in" => __("Nieprawidłowy typ transferu"),
        ];
    }
}