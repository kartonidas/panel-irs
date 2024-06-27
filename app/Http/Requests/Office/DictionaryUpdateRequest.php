<?php

namespace App\Http\Requests\Office;

use App\Http\Requests\Office\DictionaryStoreRequest;

class DictionaryUpdateRequest extends DictionaryStoreRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules["type"]);
        return $rules;
    }
}