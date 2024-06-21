<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Libraries\ValidatorExt;

class Pesel implements Rule
{
    public function passes($attribute, $value)
    {
        return ValidatorExt::pesel($value);
    }

    public function message()
    {
        return 'Pesel jest nieprawidłowy';
    }
}