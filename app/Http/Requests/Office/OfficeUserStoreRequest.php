<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Rules\OfficeUserLogin;
use App\Models\OfficePermission;

class OfficeUserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $data = $this->all();
        
        $permissionIds = OfficePermission::pluck("id")->all();
        
        $rule = [];
        $rule["user.name"] = "required";
        $rule["user.email"] = ["required", "email", new OfficeUserLogin($this->id ?? 0)];
        $rule["user.active"] = "sometimes|boolean";
        $rule["user.change_password"] = ["sometimes", "boolean"];
        $rule["user.office_permission_id"] = ["required", Rule::in($permissionIds)];

        if(empty($this->id) || !empty($data["user"]["change_password"]))
        {
            $rule["user.password"] = ["required", Password::min(8)->letters()->mixedCase()->numbers()->symbols()];
            $rule["user.password_2"] = "required|same:user.password";
        }
        
        return $rule;
    }
    
    public function messages(): array
    {
        return [
            "user.name.required" => __("Uzupełnij nazwę użytkownika"),
            "user.email.required" => __("Uzupełnij adres e-mail"),
            "user.email.email" => __("Nieprawidłowy adres e-mail"),
            "user.password.required" => __("Uzupełnij hasło"),
            "user.password.min" => __("Hasło musi składać się z co najmniej :min znaków"),
            "password.letters" => __("Hasło musi zawierać przynajmniej jedną literę"),
            "password.mixed" => __("Hasło musi zawierać przynajmniej jedną wielką i jedną małą literę"),
            "password.numbers" => __("Hasło musi zawierać przynajmniej jedną liczbę"),
            "password.symbols" => __("Hasło musi zawierać przynajmniej jeden symbol"),
            "user.password_2.required" => __("Potwierdź hasło"),
            "user.password_2.same" => __("Hasła nie są identyczne"),
            "user.office_permission_id.required" => __("Wybierz uprawnienia"),
            "user.office_permission_id.in" => __("Nieprawidłowe uprawnienia"),
        ];
    }
}