<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class CaseDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "name" => ["required", "max:250"],
            "id" => ["nullable", "integer"],
        ];
        
        if(empty($this->id))
            $rules["document"] = ["required", File::types(config("files.case_allowed_extensions"))->max(config("files.case_allowed_extensions_max_size") * 1024)];
        else
        {
            $rules["replace_file"] = ["nullable", "boolean"];
            if(!empty($this->replace_file))
                $rules["document"] = ["required", File::types(config("files.case_allowed_extensions"))->max(config("files.case_allowed_extensions_max_size") * 1024)];
        }
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "name.required" => __("Uzupełnij nazwę pliku"),
            "name.max" => __("Maksymalna długość w polu nazwa pliku to :max znaków"),
            "document.mimes" => __("Nieprawidłowy format pliku (akceptowane pliki: :mimes)", ["mimes" => implode(", ", config("files.case_allowed_extensions"))]),
            "document.max" => __("Maksymalny rozmiar pliku :max mb", ["max" => config("files.case_allowed_extensions_max_size")]),
        ];
    }
}