<?php

namespace App\Libraries;

use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

use App\Models\Customer;

class FTP
{
    public const CONNECTION_TYPE = "sftp";
    
    private static $ftp = false;
    public static $lastError = "";
    
    public static function getFTPDriver(Customer $customer)
    {
        if(!static::$ftp)
        {
            $config = $customer->sftp()->first();
            $config = $config?->toArray();
            
            if(empty($config))
                throw new Exception(__("Brak konfiguracji SFTP"));
            
            $password = $config["password"] ?? "";
            try {
                $password = Crypt::decryptString($password);
            } catch (DecryptException $e) {}
            
            static::$ftp = Storage::createFtpDriver([
                "driver" => self::CONNECTION_TYPE,
                "host" => $config["host"],
                "username" => $config["login"],
                "password" => $password,
                "port" => !empty($config["port"]) ? intval($config["port"]) : 21,
                "ssl" => !empty($config["ssl"]),
                "passive" => $config["transfer_type"] == "passive",
                "root" => !empty($config["path"]) ? $config["path"] : "/",
            ]);
        }
        return static::$ftp;
    }

    public static function testConfiguration($config)
    {
        $password = $config["password"] ?? "";
        try {
            $password = Crypt::decryptString($password);
        } catch (DecryptException $e) {}
        
        $status = false;
        $ftp = Storage::createFtpDriver([
            "driver" => self::CONNECTION_TYPE,
            "host" => $config["host"],
            "username" => $config["login"],
            "password" => $password,
            "port" => !empty($config["port"]) ? intval($config["port"]) : 21,
            "ssl" => !empty($config["ssl"]),
            "passive" => $config["transfer_type"] == "passive",
            "root" => !empty($config["path"]) ? $config["path"] : "/",
        ]);

        try
        {
            $file = bin2hex(openssl_random_pseudo_bytes(16)) . ".txt";
            $ftp->put($file, "SFTP TESTING CONNECTION...");
            $ftp->delete($file);
            $status = true;
        }
        catch(\Throwable $e)
        {
            static::$lastError = $e->getMessage();
        }
        return $status;
    }
}