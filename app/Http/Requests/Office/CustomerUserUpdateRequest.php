<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Office\CustomerUserStoreRequest;
use App\Rules\CustomerUserLogin;

class CustomerUserUpdateRequest extends CustomerUserStoreRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules["change_password"] = ["sometimes", "boolean"];
        $rules["email"] = ["required", "email", new CustomerUserLogin($this->uid)];
        
        if(empty($this->change_password))
        {
            unset($rules["password"]);
            unset($rules["password_2"]);
        }
        
        return $rules;
    }
}