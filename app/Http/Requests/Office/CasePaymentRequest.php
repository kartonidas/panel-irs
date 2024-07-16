<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;

class CasePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "date" => ["required", "date_format:Y-m-d"],
            "amount" => ["required", "numeric", "min:0.01"],
            "id" => ["nullable", "integer"],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "date.required" => __("Uzupełnij datę wniosku egzekucyjnego"),
            "date.date_format" => __("Nieprawidłowy format daty wniosku egzekucyjnego (Y-m-d)"),
            "amount.required" => __("Uzupełnij kwotę wpłaty"),
            "amount.numeric" => __("Nieprawidłowa wartość w polu kwota wpłaty"),
            "amount.min" => __("Minimalna wartość w polu kwota wpłaty to :min"),
        ];
    }
}