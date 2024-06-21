<?php

namespace App\Exceptions;

use Exception;

class AccessDeniedException extends Exception
{
    public function render($request)
    {
        return view("panel.exceptions.access-denied", ["exception" => $this]);
    }
}