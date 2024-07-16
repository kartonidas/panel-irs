<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;

class CourtStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "name" => ["required", "max:250"],
            "street" => ["nullable", "max:500"],
            "zip" => ["nullable", "max:200"],
            "city" => ["nullable", "max:200"],
            "phone" => ["nullable", "max:200"],
            "fax" => ["nullable", "max:200"],
            "email" => ["nullable", "max:200"],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "name.required" => __("Uzupełnij nazwę sądu"),
            "street.max" => __("Maksymalna długość w polu adres to : max znaków"),
            "zip.max" => __("Maksymalna długość w polu kod pocztowy to :max znaków"),
            "city.max" => __("Maksymalna długość w polu miejscowość to :max znaków"),
            "phone.max" => __("Maksymalna długość w polu telefon to :max znaków"),
            "fax.max" => __("Maksymalna długość w polu fax to :max znaków"),
            "email.max" => __("Maksymalna długość w polu adres e-mail to :max znaków"),
        ];
    }
}