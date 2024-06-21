<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Rules\Nip;
use App\Rules\Regon;

class CustomerStoreRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $caseNumbers = [];
        if(!empty($this->case_numbers))
        {
            $numbers = str_replace([";", " ", PHP_EOL], ",", $this->case_numbers);
            $numbers = array_filter(array_map("trim", explode(",", $numbers)));
            $numbers = array_values($numbers);
            $caseNumbers = $numbers;
            
            $caseNumbers = array_unique($caseNumbers);
        }
        
        $this->merge(["case_numbers" => $caseNumbers]);
    }
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "name" => ["required", "max:250"],
            "street" => ["required", "max:250"],
            "house_no" => ["required", "max:50"],
            "apartment_no" => ["sometimes", "max:50"],
            "city" => ["required", "max:120"],
            "zip" => ["required", "max:10", "regex:/^[0-9]{2}-[0-9]{3}$/"],
            "nip" => ["required", new Nip],
            "regon" => ["required", new Regon],
            "kr" => ["required"],
            "active" => ["sometimes", "boolean"],
            "case_numbers" => ["sometimes", "array"],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "name.required" => __("Uzupełnij nazwę klienta"),
            "name.max" => __("Maksymalna długość w polu nazwa klienta to : max znaków"),
            "street.required" => __("Uzupełnij adres"),
            "street.max" => __("Maksymalna długość w polu adres to :max znaków"),
            "house_no.required" => __("Uzupełnij numer domu"),
            "house_no.max" => __("Maksymalna długość w polu numer domu to :max znaków"),
            "apartment_no.max" => __("Maksymalna długość w polu numer lokalu to :max znaków"),
            "city.required" => __("Uzupełnij miejscowość"),
            "city.max" => __("Maksymalna długość w polu miejscowość to :max znaków"),
            "zip.required" => __("Uzupełnij kod pocztowy"),
            "zip.max" => __("Maksymalna długość w polu kod pocztowy to :max znaków"),
            "zip.regex" => __("Nieprawidłowy format kodu pocztowego"),
            "nip.required" => __("Uzupełnij NIP"),
            "regon.required" => __("Uzupełnij REGON"),
            "kr.required" => __("Uzupełnij KR"),
            "case_numbers.required" => __("Uzupełnij oznaczenia numerów spraw"),
            "case_numbers.array" => __("Nieprawidłowa wartość w polu oznaczenie numerów spraw"),
        ];
    }
}