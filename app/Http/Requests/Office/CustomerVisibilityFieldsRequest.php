<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Libraries\Data;

class CustomerVisibilityFieldsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $fieldsVisibility = Data::getFieldsVisibility();
        $rules = [
            "visibility.saldo" => ["sometimes", "array", Rule::in(array_keys($fieldsVisibility["saldo"]["fields"]))],
            "visibility.wplaty" => ["sometimes", "array", Rule::in(array_keys($fieldsVisibility["wplaty"]["fields"]))],
            "visibility.dluznik" => ["sometimes", "array", Rule::in(array_keys($fieldsVisibility["dluznik"]["fields"]))],
            "visibility.faktury" => ["sometimes", "array", Rule::in(array_keys($fieldsVisibility["faktury"]["fields"]))],
            "visibility.dokumenty" => ["sometimes", "array", Rule::in(array_keys($fieldsVisibility["dokumenty"]["fields"]))],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "visibility.saldo.in" => __("Nieprawidłowa wartość w sekcji 'Saldo'"),
            "visibility.wplaty.in" => __("Nieprawidłowa wartość w sekcji 'Wpłaty w sprawie'"),
            "visibility.dluznik.in" => __("Nieprawidłowa wartość w sekcji 'Dane dłużnika'"),
            "visibility.faktury.in" => __("Nieprawidłowa wartość w sekcji 'Faktury / usługi'"),
            "visibility.dokumenty.in" => __("Nieprawidłowa wartość w sekcji 'Dokumenty w sprawie'"),
        ];
    }
}