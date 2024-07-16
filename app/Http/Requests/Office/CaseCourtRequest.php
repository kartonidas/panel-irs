<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Models\Court;
use App\Models\Dictionary;

class CaseCourtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $caseStatuses = Dictionary::getByType("case_status");
        $caseModes = Dictionary::getByType("case_mode");
        
        $courtIds = Court::pluck("id")->all();
        $rules = [
            "signature" => ["required", "max:200"],
            "court_id" => ["required", Rule::in($courtIds)],
            "department" => ["required", "max:250"],
            "court_street" => ["required", "max:500"],
            "court_zip" => ["required", "max:200"],
            "court_city" => ["required", "max:200"],
            "status_id" => ["required", Rule::in(array_keys($caseStatuses))],
            "mode_id" => ["required", Rule::in(array_keys($caseModes))],
            "date" => ["required", "date_format:Y-m-d"],
            "date_enforcement" => ["nullable", "date_format:Y-m-d"],
            "date_execution" => ["nullable", "date_format:Y-m-d"],
            "cost_representation_court_proceedings" => ["nullable", "numeric", "min:0.01"],
            "cost_representation_clause_proceedings" => ["nullable", "numeric", "min:0.01"],
            "code_epu_warranty" => ["sometimes", "max:150"],
            "code_epu_clause" => ["sometimes", "max:150"],
            "code_epu_files" => ["sometimes", "max:150"],
            "id" => ["nullable", "integer"],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            
            "signature.required" => __("Uzupełnij syganturę akt"),
            "signature.max" => __("Maksymalna długość w polu sygantura akt to :max znaków"),
            "court_id.required" => __("Wybierz sąd"),
            "court_id.in" => __("Nieprawidłowa wartość w polu sąd"),
            "department.required" => __("Uzupełnij wydział"),
            "department.max" => __("Maksymalna długość w polu wydział to :max znaków"),
            "court_street.required" => __("Uzupełnij adres"),
            "court_street.max" => __("Maksymalna długość w polu adres to :max znaków"),
            "court_zip.required" => __("Uzupełnij kod pocztowy"),
            "court_zip.max" => __("Maksymalna długość w polu kod pocztowy to :max znaków"),
            "court_city.required" => __("Uzupełnij miejscowość"),
            "court_city.max:200" => __("Maksymalna długość w polu miejscowość to :max znaków"),
            "status_id.required" => __("Wybierz status"),
            "status_id.in" => __("Nieprawidłowy status"),
            "mode_id.required" => __("Wybierz tryb postępowania"),
            "mode_id.in" => __("Nieprawidłowy tryb postępowania"),
            "date.required" => __("Uzupełnij datę pozwu"),
            "date.date_format" => __("Nieprawidłowy format daty pozwu (Y-m-d)"),
            "date_enforcement.date_format" => __("Nieprawidłowy format daty uzyskania tytułu egzekucyjnego (Y-m-d)"),
            "date_execution.date_format" => __("Nieprawidłowy format daty uzyskania tytułu wykonawczego (Y-m-d)"),
            "cost_representation_court_proceedings.numeric" => __("Nieprawidłowa wartość w polu koszty zastępstwa w postępowaniu sądowym"),
            "cost_representation_court_proceedings.min" => __("Minimalna wartość w polu koszty zastępstwa w postępowaniu sądowym to :min"),
            "cost_representation_clause_proceedings.numeric" => __("Nieprawidłowa wartość w polu koszty zastępstwa w postępowaniu klauzulowym"),
            "cost_representation_clause_proceedings.min" => __("Minimalna wartość w polu koszty zastępstwa w postępowaniu klauzulowym to :min"),
            "code_epu_warranty.max" => __("Maksymalna długość w polu kod nakazu EPU to :max znaków"),
            "code_epu_clause.max" => __("Maksymalna długość w polu kod klauzuli EPU to :max znaków"),
            "code_epu_files.max" => __("Maksymalna długość w polu kod dostępu do akt EPU to :max znaków"),
        ];
    }
}