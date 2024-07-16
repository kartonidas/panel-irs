<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Models\Dictionary;

class CaseEnforcementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $caseExecutionStatuses = Dictionary::getByType("case_execution_status");
        
        $rules = [
            "signature" => ["required", "max:200"],
            "baliff" => ["required", "max:250"],
            "baliff_street" => ["required", "max:200"],
            "baliff_zip" => ["required", "max:50"],
            "baliff_city" => ["required", "max:200"],
            "execution_status_id" => ["required", "integer", Rule::in(array_keys($caseExecutionStatuses))],
            "date" => ["required", "date_format:Y-m-d"],
            "cost_representation_execution_proceedings" => ["nullable", "numeric", "min:0.01"],
            "enforcement_costs" => ["nullable", "numeric", "min:0.01"],
            "date_against_payment" => ["nullable", "date_format:Y-m-d"],
            "date_ineffective" => ["nullable", "date_format:Y-m-d"],
            "date_another_redemption" => ["nullable", "date_format:Y-m-d"],
            "id" => ["nullable", "integer"],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "signature.required" => __("Uzupełnij syganturę akt"),
            "signature.max" => __("Maksymalna długość w polu sygantura akt to :max znaków"),
            "baliff.required" => __("Uzupełnij nazwę komornika"),
            "baliff.max" => __("Maksymalna długość w polu nazwa komornika to :max znaków"),
            "baliff_street.required" => __("Uzupełnij ulicę komornika"),
            "baliff_street.max" => __("Maksymalna długość w polu ulica komornika to :max znaków"),
            "baliff_zip.required" => __("Uzupełnij kod pocztowy komornika"),
            "baliff_zip.max" => __("Maksymalna długość w polu kod pocztowy komornika to :max znaków"),
            "baliff_city.required" => __("Uzupełnij miasto komornika"),
            "baliff_city.max" => __("Maksymalna długość w polu miasto komornika to :max znaków"),
            "execution_status_id.required" => __("Uzupełnij status egzekucji"),
            "execution_status_id.integer" => __("Nieprawidłowy status egzekucji"),
            "execution_status_id.in" => __("Nieprawidłowy status egzekucji"),
            "date.required" => __("Uzupełnij datę wniosku egzekucyjnego"),
            "date.date_format" => __("Nieprawidłowy format daty wniosku egzekucyjnego (Y-m-d)"),
            "cost_representation_execution_proceedings.numeric" => __("Nieprawidłowa wartość w polu koszty zastępstwa w postępowaniu egzekucyjnym"),
            "cost_representation_execution_proceedings.min" => __("Minimalna wartość w polu koszty zastępstwa w postępowaniu egzekucyjnym to :min"),
            "enforcement_costs.numeric"  => __("Nieprawidłowa wartość w polu zaliczki / koszty egzekucyjne"),
            "enforcement_costs.min" => __("Minimalna wartość w polu zaliczki / koszty egzekucyjne to :min"),
            "date_against_payment.date_format" => __("Nieprawidłowy format daty odnotowania postanowienia o zakończeniu wobec zapłaty (Y-m-d)"),
            "date_ineffective.date_format" => __("Nieprawidłowy format daty odnotowania postanowienia o umorzeniu wobec stwierdzenia bezskuteczności egzekucji (Y-m-d)"),
            "date_another_redemption.date_format" => __("Nieprawidłowy format daty ata odnotowania innego umorzenia (Y-m-d)"),
        ];
    }
}