<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Libraries\ValidatorExt;

class Regon implements Rule
{
    public function passes($attribute, $value)
    {
        return ValidatorExt::regon($value);
    }

    public function message()
    {
        return __("Podany numer REGON jest nieprawidłowy");
    }
}