<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

use App\Rules\CustomerUserLogin;

class CustomerUserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            "firstname" => ["required", "max:200"],
            "lastname" => ["required", "max:200"],
            "email" => ["required", "email", new CustomerUserLogin],
            "password" => ["required", Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            "password_2" => ["required", "same:password"],
            "active" => ["sometimes", "boolean"],
        ];
    }
    
    public function messages(): array
    {
        return [
            "firstname.required" => __("Uzupełnij imię użytkownika"),
            "firstname.max" => __("Maksymalna długość w polu imię użytkownika to :max znaków"),
            "lastname.required" => __("Uzupełnij nazwisko użytkownika"),
            "lastname.max" => __("Maksymalna długość w polu nazwisko użytkownika to :max znaków"),
            "email.required" => __("Uzupełnij adres e-mail użytkownika"),
            "email.email" => __("Nieprawidłowy adres e-mail użytkownika"),
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