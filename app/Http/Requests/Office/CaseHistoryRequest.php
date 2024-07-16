<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Models\Dictionary;

class CaseHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "date" => ["required", "date_format:Y-m-d"],
            "history_action_id" => ["required", "integer", Rule::in(array_keys(Dictionary::getByType("case_history_action")))],
            "description" => ["sometimes", "max:5000"],
            "id" => ["nullable", "integer"],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "date.required" => __("Uzupełnij datę wystawienia"),
            "date.date_format" => __("Nieprawidłowy format daty w polu data wystawienia"),
            "history_action_id.required" => __("Wybierz czynność"),
            "history_action_id.integer" => __("Nieprawidłowa czynność"),
            "action.in" => __("Nieprawidłowa czynność"),
            "description.max" => __("Maksymalna długość w polu opis to :max znaków"),
        ];
    }
}