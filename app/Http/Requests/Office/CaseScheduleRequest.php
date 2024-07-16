<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CaseScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "amount" => ["required", "numeric", "min:0.01"],
            "date" => ["required", "date_format:Y-m-d"],
            "id" => ["nullable", "integer"],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "amount.required" => __("Uzupełnij wysokość deklarowanej raty"),
            "amount.numeric" => __("Nieprawidłowa wartość w polu wysokość deklarowanej raty"),
            "amount.min" => __("Minimalna wartość wysokość deklarowanej wpłaty to :min"),
            "date.required" => __("Uzupełnij datę wystawienia"),
            "date.date_format" => __("Nieprawidłowy format daty w polu data wystawienia"),
        ];
    }
}