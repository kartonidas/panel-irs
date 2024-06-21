<?php

namespace App\Exceptions;

use Exception;

class OfficeAccessDeniedException extends Exception
{
    public function render($request)
    {
        return view("office.exceptions.access-denied", ["exception" => $this]);
    }
}