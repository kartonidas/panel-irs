<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

use App\Exceptions\OfficeAccessDeniedException;
use App\Models\OfficePermission;

class OfficeUser extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
    
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
}