<?php

namespace App\Libraries;

use DateInterval;
use DateTime;
use DatePeriod;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use App\Libraries\Holiday;

class Helper
{
    public static function objectToArray($array)
    {
        return json_decode(json_encode($array), true);
    }

    public static function setMessage($module, $msg, $type = "Message")
    {
        $sessionKeyName = $type . ":" . $module;

        $message = request()->session()->get($sessionKeyName, "");
        $message = $message ? unserialize($message) : [];
        $message[] = $msg;
        request()->session()->put($sessionKeyName, serialize($message));
    }

    public static function getMessage($module, $type = "Message")
    {
        $sessionKeyName = $type . ":" . $module;
        $message = request()->session()->get($sessionKeyName, "");
        $message = $message ? unserialize($message) : [];

        request()->session()->forget($sessionKeyName);

        return $message;
    }

    public static function validateDate($date, $format = "Y-m-d") {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function amount($amount) {
        return number_format($amount, 2, ",", "");
    }
    
    public static function calculateGrossAmount($net_amount, $vat_rate = 0)
    {
        $net_amount = str_replace(",", ".", $net_amount);
        if(!is_numeric($net_amount) || $net_amount < 0)
            $net_amount = 0;

        $gross_amount = $net_amount;

        if($vat_rate)
            $gross_amount = $net_amount * ((100 + $vat_rate) / 100);

        return $gross_amount;
    }

    // Tworzy wielopoziomową tablicę na podstawie kluczy rozdzielonych kropką
    public static function makeArray($key, &$array, $value)
    {
        if(strpos($key, ".") !== false)
        {
            $keys = explode(".", $key);
            foreach($keys as $i => $k)
                $array = &$array[$k];
        }
        else
            $array = &$array[$key];

        $array = $value;
    }

    public static function plurals($cnt, $f1, $f2, $f3) {
		if($cnt == 1) return $f1;

        $div1 = $cnt % 10;
        if($div1 <= 1 || $div1 >= 5) return $f3;

        $div2 = ($cnt-$div1)/10 % 10;
        if($div2 == 1) return $f3;

        return $f2;
	}

    public static function __no_pl($tekst, $replaceExtraChar = true) {
		$tabela = Array(
		//WIN
		"\xb9" => "a", "\xa5" => "A", "\xe6" => "c", "\xc6" => "C", "\xea" => "e", "\xca" => "E", "\xb3" => "l", "\xa3" => "L", "\xf3" => "o", "\xd3" => "O", "\x9c" => "s", "\x8c" => "S", "\x9f" => "z", "\xaf" => "Z", "\xbf" => "z", "\xac" => "Z", "\xf1" => "n", "\xd1" => "N",
		//UTF
		"\xc4\x85" => "a", "\xc4\x84" => "A", "\xc4\x87" => "c", "\xc4\x86" => "C", "\xc4\x99" => "e", "\xc4\x98" => "E", "\xc5\x82" => "l", "\xc5\x81" => "L", "\xc3\xb3" => "o", "\xc3\x93" => "O", "\xc5\x9b" => "s", "\xc5\x9a" => "S", "\xc5\xbc" => "z", "\xc5\xbb" => "Z", "\xc5\xba" => "z", "\xc5\xb9" => "Z", "\xc5\x84" => "n", "\xc5\x83" => "N",
		//ISO
		"\xb1" => "a", "\xa1" => "A", "\xe6" => "c", "\xc6" => "C", "\xea" => "e", "\xca" => "E", "\xb3" => "l", "\xa3" => "L", "\xf3" => "o", "\xd3" => "O", "\xb6" => "s", "\xa6" => "S", "\xbc" => "z", "\xac" => "Z", "\xbf" => "z", "\xaf" => "Z", "\xf1" => "n", "\xd1" => "N");

		if($replaceExtraChar) {
			$tekst = str_replace(array(" ", "?", "/", "\\"), array("-"), $tekst);
			$tekst = str_replace(array("'", "\"", "#", "&"), array(""), $tekst);
			return strtolower(strtr(self::CyrilicToLatin($tekst), $tabela));
		}
		return strtr(self::CyrilicToLatin($tekst), $tabela);
	}

    public static function CyrilicToLatin($textcyr) {
        $cyr  = array('а','б','в','г','д','e','ж','з','и','й','к','л','м','н','о','п','р','с','т','у',
          'ф','х','ц','ч','ш','щ','ъ','ь', 'ю','я','А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У',
          'Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ь', 'Ю','Я' );
        $lat = array( 'a','b','v','g','d','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u',
          'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'a' ,'y' ,'yu' ,'ya','A','B','V','G','D','E','Zh',
          'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
          'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y' ,'Yu' ,'Ya' );
        return str_replace($cyr, $lat, $textcyr);
    }

    public static function slownie($x, $currency = "zł") {
        if($x < 0) $x = -1 * $x;
        $ss = array("","sto ","dwieście ","trzysta ","czterysta ","pięcset ","sześćset ","siedemset ","osiemset ","dziewięćset ");
        $dd = array("","dziesięć ","dwadzieścia ","trzydzieści ","czterdzieści ","pięćdziesiąt ","sześćdziesiąt ","siedemdziesiąt ","osiemdziesiąt ","dziewięćdziesiąt ");
        $jj = array("","jeden ","dwa ","trzy ","cztery ","pięć ","sześć ","siedem ","osiem ","dziewięć ","dziesięć ","jedenaście ","dwanaście ","trzynaście ","czternaście ","piętnaście ","szesnaście ","siedemnaście ","osiemnaście ","dziewiętnaście ");

        $x = number_format($x, 2, ".", "");

        $buf = explode(",",str_replace(".",",",$x));

        $w = "";

        if($buf[0]>=1000000) {
            $l = (int)($buf[0]/1000000);
            $w .= $ss[$l/100];

            if($l == 1) $k = 'milon ';
            elseif($l%10>1 && $l%10<5 && ($l<10 || $l>20)) $k = 'miliony ';
            else $k = 'milonów ';

            $l = $l - 100*(int)($l/100);

            if((int)$l<20)
                $w .= $jj[$l];
            else
                $w .= $dd[substr($l,0,1)].$jj[substr($l,1,1)];
            $w .= $k;
        }

        $buf[0] = $buf[0] - 1000000*(int)($buf[0]/1000000);

        if($buf[0]>=1000) {
            $l = (int)($buf[0]/1000);
            $w .= $ss[$l/100];

            if($l == 1) $k = 'tysiąc ';
            elseif($l%10>1 && $l%10<5 && ($l<10 || $l>20)) $k = 'tysiące ';
            else $k = 'tysięcy ';

            $l = $l - 100*(int)($l/100);

            if((int)$l<20)
                $w .= $jj[$l];
            else
                $w .= $dd[substr($l,0,1)].$jj[substr($l,1,1)];
            $w .= $k;
        }

        $buf[0] = $buf[0] - 1000*(int)($buf[0]/1000);

        if($buf[0]>=1) {
            $l = $buf[0];

            $w .= $ss[$l/100];

            $l = $l - 100*(int)($l/100);

            if((int)$l<20)
                $w .= $jj[$l];
            else
                $w .= $dd[substr($l,0,1)].$jj[substr($l,1,1)];
        }
        $w .= ' ' . $currency . ' ';
        if($buf[1] > 0) $w .= ($w ? "i " : "").$buf[1]."/100";
        else $w .= "i zero";

        return $w;
    }

    public static function calculateWorkingDays($d1, $d2)
    {
        $objStartDate = new DateTime($d1);
        $objEndDate = new DateTime($d2);
        $interval = new DateInterval("P1D");
        $dateRange = new DatePeriod($objStartDate, $interval, $objEndDate);

        $count = 0;
        foreach ($dateRange as $eachDate) {
            if($eachDate->format("w") != 6 && $eachDate->format("w") != 0)
            {
                if(!\App\Libraries\Holiday::isHoliday($eachDate))
                    $count++;
            }
        }
        return $count;
    }

    public static function getNextWorkingDay($d1)
    {
        $date = new DateTime($d1);
        $date->add(new DateInterval("P1D"));
        if($date->format("w") == 6 || $date->format("w") == 0 || Holiday::isHoliday($date))
            return self::getNextWorkingDay($date->format("Y-m-d"));
        return $date->format("Y-m-d");
    }

    public static function dateDiff($d1, $d2)
    {
        $objStartDate = new DateTime($d1);
        $objEndDate = new DateTime($d2);

        return $objEndDate->diff($objStartDate)->format("%a");
    }
    
    public static function uploadFiles(Request $request)
    {
        if(!Auth::guard("backend")->check())
            return;

        $userRow = Auth::user();

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        @set_time_limit(5 * 60);

        $targetDir = storage_path("tmp/");
        $cleanupTargetDir = true;
        $maxFileAge = 60;

        if (!file_exists($targetDir))
            @mkdir($targetDir);

        $fileName = $request->input("name", "");
        if(!$fileName)
            $fileName = $request->file("file")->getClientOriginalName();
        if(!$fileName)
            $fileName = uniqid("file_");

        $tmpFileName = preg_replace('/[^a-zA-Z0-9.]/', '_', $fileName);

        $filePath = $targetDir . $tmpFileName;

        $chunk = $request->input("chunk", 0);
        $chunks = $request->input("chunks", 0);

        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . "/" . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}.part") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }

        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb"))
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');

        if($request->hasFile("file")) {
            if(!is_uploaded_file($request->file("file")->getPathName()))
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($request->file("file")->getPathName(), "rb"))
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
        } else {
            if (!$in = @fopen("php://input", "rb"))
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        $completed = false;
        if (!$chunks || $chunk == $chunks - 1) {
            $tmpFileName = bin2hex(openssl_random_pseudo_bytes(16));
            rename("{$filePath}.part", $targetDir . $tmpFileName);
            $completed = true;
        }

        $out = [
            "jsonrpc" => "2.0",
            "result" => null,
            "id" => "id",
            "completed" => $completed,
            "file" => [
                "tmp_name" => $tmpFileName,
                "extension" => substr($fileName, strrpos($fileName, ".") + 1),
                "orig_name" => $fileName,
            ]
        ];
        return $out;
    }
    
    public static function getUniqueName($file, $path)
    {
        $origFilename = $file;
        $path = rtrim($path, "/");
        
        if(file_exists($path . "/" . $file))
        {
            $index = 1;
            while(true)
            {
                $pathParts = pathinfo($path . "/" . $origFilename);
                $file = $pathParts["filename"] . "-" . ($index++) . "." . $pathParts["extension"];
                if(!file_exists($path . "/" . $file))
                    break;
            }
        }
            
        return $file;
    }
    
    public static function removeGeneratedThumbs($dir, $file)
    {
        $dir = rtrim($dir, "/");
        foreach(config("images.thumbs") as $thumbDir)
        {
            if(is_dir($dir . "/" . $thumbDir))
            {
                if(file_exists($dir . "/" . $thumbDir . "/" . $file))
                    unlink($dir . "/" . $thumbDir . "/" . $file);
            }
        }
    }
    
    public static function formatBytes($bytes, $precision = 2)
    { 
        $base = log($bytes, 1024);
        $suffixes = array("", "KB", "MB", "GB", "TB");   
        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }
    
    public static function getBytes($val)
    {
        if (empty($val)) 
            $val = 0;
            
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = floatval($val);
        switch($last)
        {
            case 'g':
                $val *= (1024 * 1024 * 1024); 
                break;
            case 'm':
                $val *= (1024 * 1024);
                break;
            case 'k':
                $val *= 1024;
                break;
        }
    
        return $val;
    }
    
    public static function getUploadMaxFileSize($format = false)
    {
        $maxSystemSize = self::getBytes(ini_get("upload_max_filesize"));
        $configSize = config("site.debts.objection.max_size");
        
        $max = $configSize <= $maxSystemSize ? $configSize : $maxSystemSize;
        
        return $format ? self::formatBytes($max) : $max;
    }
    
    public static function prepareEmails(string $emails) : array
    {
        $emails = str_replace([" ", ";"], ",", $emails);
        $emails = explode(",", $emails);
        $emails = array_filter(array_map("trim", $emails));
        
        $out = [];
        foreach($emails as $email)
        {
            if (filter_var($email, FILTER_VALIDATE_EMAIL))
                $out[] = $email;
        }
        return $out;
    }
    
    public static function externalUrl($content)
    {
        $pattern = '#<a[^>]*href=[\'"]([^\'"]*)[\'"][^>]*>(((?!<a\s).)*)</a>#i';
        return preg_replace_callback($pattern, function($matches) {
            $domains = [
                preg_quote(request()->getHost())
            ];
            $urlHost = parse_url($matches[1], PHP_URL_HOST);
            
            if($urlHost && !preg_match("/^" . implode("|", $domains) . "$/i", $urlHost))
                return "<a href=\"" . route("external", ["url" => $matches[1]]) . "\" target=\"_blank\">" . $matches[1] . "</a>";
            else
                return $matches[0];
        }, $content);
        
        return;
    }
    
    public static function getUrlToSortable(Request $request, $field)
    {
        $sortQuery = ["sort" => $field . ",asc"];
        $sort = $request->get("sort", null);
        if(!empty($sort) && count(explode(",", $sort)) == 2)
        {
            list($column, $direction) = explode(",", $sort);
            if($column == $field)
            {
                $direction = $direction == "asc" ? "desc" : "asc";
                $sortQuery = ["sort" => $field . "," . $direction];
            }
        }
        return $request->fullUrlWithQuery($sortQuery);
    }
    
    public static function normalizeNipPesel($value)
    {
        return trim(preg_replace("/-|\s/", "", $value));
    }
    
    public static function getMd5Checksum($data)
    {
        ksort($data);
        return md5(serialize($data) . base64_encode(env("APP_KEY")));
    }
    
    public static function validateMd5Checksum($data)
    {
        if(!isset($data["md5"]))
            return false;
        
        $md5 = $data["md5"];
        unset($data["md5"]);
        
        if(self::getMd5Checksum($data) == $md5)
            return true;
        
        return false;
    }
    
    public static function getAddress($street, $house_no, $apartment_no, $zip, $city, $delimiter = ", ")
    {
        $parts = [];
        
        $streetParts = [];
        $streetParts[] = $street;
        $streetHouseApartmentParts = [];
        if(!empty($house_no))
            $streetHouseApartmentParts[] = $house_no;
        if(!empty($apartment_no))
            $streetHouseApartmentParts[] = $apartment_no;
        if(!empty($streetHouseApartmentParts))
            $streetParts[] = implode("/", $streetHouseApartmentParts);
        
        $parts[] = implode(" ", $streetParts);
        $parts[] = $zip . " " . $city;
        
        return implode($delimiter, $parts);
    }
    
    public static function setPaginator($request, $page, $pageSize, $total)
    {
        return new \Illuminate\Pagination\LengthAwarePaginator(
            [],
            $total,
            $pageSize,
            $page,
            [
                "path" => url()->current(),
                "query" => $request->query(),
            ]
        );
    }
    
    public static function getPermission()
    {
        return config("office.permissions");
    }
}