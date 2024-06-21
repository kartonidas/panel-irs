<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OfficeUser;

class OfficePermission extends Model
{
    public const ROLE_ADMIN = "admin";
    public const ROLE_EMPLOYEE = "employee";
    
    public const ADMIN_PERMISSION_TYPE_FULL = "full";
    public const ADMIN_PERMISSION_TYPE_SELECTED = "selected";
    
    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => __("Administrator"),
            self::ROLE_EMPLOYEE => __("Pracownik"),
        ];
    }
    
    public static function getAdminPermissionTypes()
    {
        return [
            self::ADMIN_PERMISSION_TYPE_FULL => __("Pełen dostep"),
            self::ADMIN_PERMISSION_TYPE_SELECTED => __("Wybrane uprawnienia"),
        ];
    }
    
    public function canDelete()
    {
        return !(OfficeUser::where("office_permission_id", $this->id)->count() > 0);
    }
    
    public static function getAllowedPermissions($role)
    {
        $out = [];
        $permissions = config("office.permissions." . $role) ?? [];
        foreach($permissions as $module => $permission)
        {
            foreach($permission["operation"] as $op)
                $out[] = $module . ":" . $op;
        }
        
        return $out;
    }
    
    public function getRoleName()
    {
        return self::getRoles()[$this->role] ?? $this->role;
    }
}