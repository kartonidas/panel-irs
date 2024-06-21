<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\OfficeUser;

class OfficeUserLogin implements Rule
{
    private $uid;
    public function __construct($uid = 0)
    {
        $this->uid = $uid;
    }

    public function passes($attribute, $value)
    {
        if($this->uid)
            $cnt = OfficeUser::where("email", $value)->where("id", "!=", $this->uid)->count();
        else
            $cnt = OfficeUser::where("email", $value)->count();

        if($cnt)
            return false;

        return true;
    }

    public function message()
    {
        return __("Podany adres e-mail jest już zajęty");
    }
}
