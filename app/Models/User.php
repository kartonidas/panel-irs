<?php

namespace App\Models;

use DateTime;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Libraries\Data;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    public const DEFAULT_BLOCK_ACCOUNT_AFTER_INACTIVE_MONTH = 6;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public static function blockInactiveLongTimeAccount()
    {
        $month = env("BLOCK_ACCOUNT_AFTER_INACTIVE_MONTH", self::DEFAULT_BLOCK_ACCOUNT_AFTER_INACTIVE_MONTH);
        $time = (new DateTime())->sub(new DateInterval("P" . $month . "M"));
        
        $accountToBlock = self::whereNotNull("last_login")->where("last_login", "<", $time->getTimestamp())->where("block", 0)->get();
        foreach($accountToBlock as $accountToBlockRow)
        {
            $accountToBlockRow->block = 1;
            $accountToBlockRow->block_reason = Data::USER_BLOCK_REASON_INACTIVE_LONG_TIME;
            $accountToBlockRow->saveQuietly();
        }
    }
}
