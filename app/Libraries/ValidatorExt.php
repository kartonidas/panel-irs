<?php

    namespace App\Libraries;
    
    use App\Libraries\Helper;
    use App\Models\User;
    use App\Models\Account;

	class ValidatorExt {
        public static function nip($value) {
            if(empty($value))
                return true;

            $value = Helper::normalizeNipPesel($value);

            if(strlen($value) != 10 || preg_match("/^[0-9]{10}$/", $value) == false)
                return false;

            $arrSteps = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
            $intSum=0;
            for ($i = 0; $i < 9; $i++)
                $intSum += $arrSteps[$i] * $value[$i];
            $int = $intSum % 11;
            $intControlNr = ($int == 10) ? 0 : $int;
            if ($intControlNr !== (int)$value[9])
                return false;

            return true;
        }

        public static function pesel($value) {
            $pesel = Helper::normalizeNipPesel($value);
            
            if(empty($pesel))
                return true;

            $arrWagi = array(1, 3, 7, 9, 1, 3, 7, 9, 1, 3);
            $intSum = 0;
            for ($i = 0; $i < 10; $i++)
                $intSum += $arrWagi[$i] * (int)$pesel[$i];

            $int = 10 - $intSum % 10;
            $intControlNr = ($int == 10)?0:$int;
            if ($intControlNr == $pesel[10])
                return true;

            return false;
        }
        
        public static function regon($value) {
            $regon = trim($value);
            if(preg_match("/^[0-9]{9}$/", $regon)==false)
                return false;
    
            $digits = str_split($regon);
            $checksum = (8*intval($digits[0]) + 9*intval($digits[1]) + 2*intval($digits[2]) + 3*intval($digits[3]) + 4*intval($digits[4]) + 5*intval($digits[5]) + 6*intval($digits[6]) + 7*intval($digits[7]))%11;
            if($checksum == 10)
                $checksum = 0;
    
            if(intval($digits[8]) != $checksum)
                return false;
            
            return true;
        }
    }