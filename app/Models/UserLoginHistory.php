<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libraries\Data;
use App\Models\OfficeUser;
use App\Models\User;

class UserLoginHistory extends Model
{
    public $timestamps = false;
    public $table = "user_login_history";
    
    public const SOURCE_OFFICE = "office";
    public const SOURCE_SITE = "site";
    
    public const DEFAULT_MAX_FAIL_TRIES = 3;
    public const DEFAULT_CHECK_FAILED_PERIOD_SECONDS = 180;
    
    /*
     * Konto zostanie zablokowane jeśli 3 ostatnie próby (MAX_FAIL_TRIES)
     * w przeciągu ostatnich 180 sekund (CHECK_FAILED_PERIOD_SECONDS) były nieudane.
     */
    public static function log($request, string $email, string $source, bool $successful, bool $blockIfExceedTries = true)
    {
        $user = null;
        switch($source)
        {
            case self::SOURCE_OFFICE:
                $user = OfficeUser::where("email", $email)->first();
            break;
            case self::SOURCE_SITE:
                $user = User::where("email", $email)->first();
            break;
        }
        
        if($user && !$user->block)
        {
            $history = new self;
            $history->source = $source;
            $history->email = $email;
            $history->ip = $request->ip();
            $history->user_agent = $request->userAgent();
            $history->successful = $successful;
            $history->time = time();
            $history->save();
            
            if($blockIfExceedTries)
            {
                $failTries = 0;
                $lastTries = self::where("source", $source)->where("email", $email)->orderBy("time", "DESC")->limit(env("MAX_FAIL_TRIES", self::DEFAULT_MAX_FAIL_TRIES))->get();
                foreach($lastTries as $last)
                {
                    if(!$last->successful && $last->time + env("CHECK_FAILED_PERIOD_SECONDS", self::DEFAULT_CHECK_FAILED_PERIOD_SECONDS) > time())
                        $failTries += 1;
                }
                if($failTries == env("MAX_FAIL_TRIES", self::DEFAULT_MAX_FAIL_TRIES))
                {
                    $user->block = 1;
                    $user->block_reason = Data::USER_BLOCK_REASON_CREDENTIALS;
                    $user->saveQuietly();
                }
            }
        }
    }
}