<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Models\Currency;

class CaseClaimRequest extends FormRequest
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
            "due_date" => ["required", "date_format:Y-m-d"],
            "mark" => ["required", "max:200"],
            "description" => ["sometimes", "max:5000"],
            "currency" => ["sometimes", Rule::in(Currency::getAllowedCurrencies())],
            "id" => ["nullable", "integer"],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "amount.required" => __("Uzupełnij kwotę roszczenia"),
            "amount.numeric" => __("Nieprawidłowa wartość w polu kwota roszczenia"),
            "amount.min" => __("Minimalna kwota roszczenia to :min"),
            "date.required" => __("Uzupełnij datę wystawienia"),
            "date.date_format" => __("Nieprawidłowy format daty w polu data wystawienia"),
            "due_date.required" => __("Uzupełnij termin wymagalności"),
            "due_date.date_format" => __("Nieprawidłowy format daty w polu termin wymagalności"),
            "mark.max" => __("Maksymalna długość w polu oznaczenie roszczenia to :max znaków"),
            "description.max" => __("Maksymalna długość w polu opis to :max znaków"),
            "id.integer" => __("Nieprawidłowy identyfikator roszczenia"),
            "currency.in" => __("Nieprawidłowa waluta"),
        ];
    }
}