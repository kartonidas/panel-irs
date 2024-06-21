<?php

namespace App\Traits;

trait Form
{
    public function isApply()
    {
        if(request()->input("_apply", 0))
            return true;

        return false;
    }

    public function isPrint()
    {
        if(request()->input("_print", 0))
            return true;

        return false;
    }
}