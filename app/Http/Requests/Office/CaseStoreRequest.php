<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Models\Dictionary;

class CaseStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "customer_name" => ["required", "max:250"],
            "customer_signature" => ["required", "max:80"],
            "rs_signature" => ["required", "max:30"],
            "opponent" => ["required", "max:250"],
            "status_id" => ["required", "integer", Rule::in(array_keys(Dictionary::getByType("case_status")))],
            "death" => ["sometimes", "boolean"],
            "date_of_death" => ["sometimes", "required_if:death,1", "date_format:Y-m-d"],
            "insolvency" => ["sometimes", "boolean"],
            "completed" => ["sometimes", "boolean"],
            "baliff" => ["sometimes", "max:250"],
            "court" => ["sometimes", "max:250"],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "customer_name.required" => __("Uzupełnij nazwę klienta"),
            "customer_name.max" => __("Maksymalna długość w polu nazwa klienta to :max znaków"),
            "customer_signature.required" => __("Uzupełnij oznaczenie klienta - numer sprawy klienta"),
            "customer_signature.max" => __("Maksymalna długość w polu oznaczenie klienta - numer sprawy klienta to :max znaków"),
            "rs_signature.required" => __("Uzupełnij oznaczenie RS"),
            "rs_signature.max" => __("Maksymalna długość w polu oznaczenie RS to :max znaków"),
            "opponent.required" => __("Uzupełnij przeciwnika"),
            "opponent.max" => __("Maksymalna długość w polu przeciwnik to :max znaków"),
            "status_id.required" => __("Uzupełnij stan sprawy"),
            "status_id.integer" => __("Nieprawidłowa wartość w polu stan sprawy"),
            "status_id.in" => __("Nieprawidłowa wartość w polu stan sprawy"),
            "date_of_death.required" => __("Uzupełnij datę zgonu"),
            "date_of_death.date_format" => __("Nieprwaidłowy format w polu data zgonu (Y-m-d)"),
            "baliff.max" => __("Maksymalna długość w polu komornik to :max znaków"),
            "court.max" => __("Maksymalna długość w polu sąd to :max znaków"),
        ];
    }
}