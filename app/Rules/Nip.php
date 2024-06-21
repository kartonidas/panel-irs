<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Libraries\ValidatorExt;

class Nip implements Rule
{
    public function passes($attribute, $value)
    {
        return ValidatorExt::nip($value);
    }

    public function message()
    {
        return 'NIP jest nieprawidłowy';
    }
}