<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

use App\Libraries\Helper;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        $rules = [
            "firstname" => ["required", "max:200"],
            "lastname" => ["required", "max:200"],
        ];
        
        if(!empty($this->change_password))
        {
            $rules["password"] = ["required", Password::min(8)->letters()->mixedCase()->numbers()->symbols()];
            $rules["password_2"] = ["required", "same:password"];
        }
    
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "firstname.required" => __("Uzupełnij imię"),
            "firstname.max" => __("Maksymalna długość w polu imię to : max znaków"),
            "lastname.required" => __("Uzupełnij nazwisko"),
            "lastname.max" => __("Maksymalna długość w polu nazwisko to : max znaków"),
            "password.required" => __("Uzupełnij hasło"),
            "password.min" => __("Hasło musi składać się z co najmniej :min znaków"),
            "password.letters" => __("Hasło musi zawierać przynajmniej jedną literę"),
            "password.mixed" => __("Hasło musi zawierać przynajmniej jedną wielką i jedną małą literę"),
            "password.numbers" => __("Hasło musi zawierać przynajmniej jedną liczbę"),
            "password.symbols" => __("Hasło musi zawierać przynajmniej jeden symbol"),
            "password_2.required" => __("Potwierdź hasło"),
            "password_2.same" => __("Hasła nie są identyczne"),
        ];
    }
}