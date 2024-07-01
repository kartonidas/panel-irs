<?php

namespace App\Models;

use DateTime;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

use App\Exceptions\OfficeAccessDeniedException;
use App\Libraries\Data;
use App\Models\OfficePermission;

class OfficeUser extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;
    
    public const DEFAULT_BLOCK_ACCOUNT_AFTER_INACTIVE_MONTH = 6;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
    
    public static $sortable = ["email", "name", "active", "block"];
    public static $defaultSortable = ["name", "asc"];
    public static $filter = ["email", "name", "block"];
    
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    
    private static $userPermissionGroup = null;

	public static function checkAccess($perm, $eception = true)
	{
        $user = Auth::guard("office")->user();
        
		if(static::$userPermissionGroup === null)
		{
			$permissionGroup = OfficePermission::find($user->office_permission_id);
			if(!$permissionGroup)
			{
				if($eception)
					throw new OfficeAccessDeniedException;
				return false;
			}
            static::$userPermissionGroup = $permissionGroup;
		}
        
        if(static::$userPermissionGroup->role == OfficePermission::ROLE_ADMIN && static::$userPermissionGroup->admin_permission_type == OfficePermission::ADMIN_PERMISSION_TYPE_FULL)
            return true;
        
        list($module) = explode(":", $perm, 2);
        
        /*
         * Użytkownik jest w grupie administratorów - ma pełen dostep do sekcji pracowników
         */
        if(static::$userPermissionGroup->role == OfficePermission::ROLE_ADMIN && in_array($module, array_keys(config("office.permissions.employee"))))
            return true;
        
        /*
         * Użytkownik jest w grupie pracowników - nie może mieć uprawnień do sekcji "admin"
         */
        if(static::$userPermissionGroup->role == OfficePermission::ROLE_EMPLOYEE && in_array($module, array_keys(config("office.permissions.admin"))))
            return false;

            
        $userPermissions = explode(";", static::$userPermissionGroup->permissions);
        if(in_array($perm, $userPermissions))
			return true;
            
		if($eception)
			throw new OfficeAccessDeniedException;

		return false;
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
    
    public function isActivityTimeout() : bool
    {
        $time = time() - env("LOGOUT_INACTIVE_TIMEOUT_MIN", 15) * 60;
        return $this->last_activity < $time ? true : false;
    }
}