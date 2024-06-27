<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Models\Dictionary;

class DictionaryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "type" => ["required", Rule::in(array_keys(config("dictionaries.types")))],
            "value" => ["required"],
        ];
        
        $type = $this->type ?? null;
        if(!empty($this->id))
        {
            $dictionary = Dictionary::find($this->id);
            if($dictionary)
                $type = $dictionary->type;
        }
        
        switch($type)
        {
            case "document_type":
                $rules["extra.document_type.document_signature"] = ["required"];
            break;
        }
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "type.required" => __("Uzupełnij rodzaj"),
            "type.in" => __("Nieprawidłowy rodzaj"),
            "value.required" => __("Uzupełnij wartość"),
            "extra.document_type.document_signature.required" => __("Uzupełnij sygnaturę")
        ];
    }
}