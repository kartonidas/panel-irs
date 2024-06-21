<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Models\OfficePermission;

class PermissionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "name" => ["required"],
            "role" => ["required", Rule::in(array_keys(OfficePermission::getRoles()))],
        ];
        
        if(!empty($this->role))
        {
            if($this->role == OfficePermission::ROLE_ADMIN)
            {
                if(empty($this->admin_permission_type) || $this->admin_permission_type != OfficePermission::ADMIN_PERMISSION_TYPE_FULL)
                    $rules["permissions.admin"] = ["required", "array", Rule::in(OfficePermission::getAllowedPermissions(OfficePermission::ROLE_ADMIN))];
                    
                $rules["admin_permission_type"] = ["required", Rule::in(array_keys(OfficePermission::getAdminPermissionTypes()))];
            }
            
            if($this->role == OfficePermission::ROLE_EMPLOYEE)
                $rules["permissions.employee"] = ["required", "array", Rule::in(OfficePermission::getAllowedPermissions(OfficePermission::ROLE_EMPLOYEE))];
        }
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "name.required" => __("Uzupełnij nazwę"),
            "role.required" => __("Uzupełnij rolę"),
            "role.in" => __("Nieprawidłowa rola"),
            "permissions.admin.required" => __("Zdefiniuj uprawnienia"),
            "permissions.admin.array" => __("Nieprawidłowe uprawnienia"),
            "permissions.admin.in" => __("Nieprawidłowe uprawnienia"),
            "permissions.employee.required" => __("Zdefiniuj uprawnienia"),
            "permissions.employee.array" => __("Nieprawidłowe uprawnienia"),
            "permissions.employee.in" => __("Nieprawidłowe uprawnienia"),
            "admin_permission_type.required" => __("Wybierz rodzaj uprawnień"),
            "admin_permission_type.in" => __("Nieprawidłowy rodzaj uprawnień"),
        ];
    }
}
