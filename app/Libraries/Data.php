<?php

namespace App\Libraries;

use App\Models\Dictionary;

class Data
{
    public const USER_BLOCK_REASON_CREDENTIALS = "credentials";
    public const USER_BLOCK_REASON_INACTIVE_LONG_TIME = "inactive_long_time";
    public const USER_BLOCK_REASON_ADMIN = "admin";
    
    public static function getBlockReasons()
    {
        return [
            self::USER_BLOCK_REASON_CREDENTIALS => "Przekroczona liczba nieprawidłowych prób logowania",
            self::USER_BLOCK_REASON_INACTIVE_LONG_TIME => "Zbyt długi okres bez logowania na konto",
            self::USER_BLOCK_REASON_ADMIN => "Blokada nałożona przez amidnistratora",
        ];
    }
    
    public static function getFieldsVisibility() : array
    {
        $fieldsVisibility = config("fields-visibility");
        $fieldsVisibility["dokumenty"]["fields"] = Dictionary::getByType("document_type");
        return $fieldsVisibility;
    }
}