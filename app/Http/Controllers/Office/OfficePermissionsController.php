<?php

namespace App\Http\Controllers\Office;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Office\PermissionStoreRequest;
use App\Http\Requests\Office\PermissionUpdateRequest;
use App\Libraries\Helper;
use App\Models\OfficePermission;
use App\Models\OfficeUser;
use App\Traits\Form;

class OfficePermissionsController extends Controller
{
    use Form;

    public function list(Request $request)
    {
    	OfficeUser::checkAccess("permissions:list");
        view()->share("activeMenuItem", "permissions");

        $vData = [
            "permissions" => OfficePermission::orderBy("role", "ASC")->orderBy("name", "ASC")->paginate(config("office.lists.size")),
        ];

        return view("office.permissions.list", $vData);
    }

    public function create(Request $request)
    {
    	OfficeUser::checkAccess("permissions:create");
        view()->share("activeMenuItem", "permissions");

		$form = [
            "role" => OfficePermission::ROLE_EMPLOYEE,
            "admin_permission_type" => OfficePermission::ADMIN_PERMISSION_TYPE_SELECTED,
            "permissions" => []
		];

		$old = request()->old();
        if($old)
        {
            if(!empty($old["role"]) && !empty($old["permissions"][$old["role"]]))
                $old["permissions"] = $old["permissions"][$old["role"]];
        }
        $vData = [
            "form" => $old ? $old : $form,
            "permissions" => Helper::getPermission(),
            "roles" => OfficePermission::getRoles(),
            "adminPermissionTypes" => OfficePermission::getAdminPermissionTypes(),
        ];
        return view("office.permissions.create", $vData);
    }

    public function createPost(PermissionStoreRequest $request)
    {
    	OfficeUser::checkAccess("permissions:create");
        $validated = $request->validated();

        $row = new OfficePermission;
        $row->name = $validated["name"];
        $row->role = $validated["role"];
		$row->admin_permission_type = $validated["role"] == OfficePermission::ROLE_ADMIN ? $validated["admin_permission_type"] : null;
        if($row->role == OfficePermission::ROLE_ADMIN && $row->admin_permission_type == OfficePermission::ADMIN_PERMISSION_TYPE_FULL)
            $row->permissions = "";
        else
            $row->permissions = implode(";", $validated["permissions"][$validated["role"]]);
        $row->save();

        Helper::setMessage("permissions", __("Grupa została utworzona"));
        if($this->isApply())
            return redirect()->route("office.permission.update", $row->id);
        else
            return redirect()->route("office.permissions");
    }

    public function update(Request $request, $id)
    {
    	OfficeUser::checkAccess("permissions:update");
        view()->share("activeMenuItem", "permissions");

        $row = OfficePermission::find($id);
        if(!$row)
            return redirect()->route("office.permissions")->withErrors(["msg" => __("Rekord nie istnieje")]);

        $form = [
            "name" => $row->name,
            "role" => $row->role,
            "permissions" => explode(";", $row->permissions),
            "admin_permission_type" => $row->admin_permission_type,
        ];

        $old = request()->old();
        if($old)
        {
            if(!empty($old["role"]) && !empty($old["permissions"][$old["role"]]))
                $old["permissions"] = $old["permissions"][$old["role"]];
        }
        $vData = [
            "id" => $id,
            "form" => $old ? $old : $form,
            "permissions" => Helper::getPermission(),
            "roles" => OfficePermission::getRoles(),
            "adminPermissionTypes" => OfficePermission::getAdminPermissionTypes(),
        ];
        return view("office.permissions.update", $vData);
    }

    public function updatePost(PermissionUpdateRequest $request, $id)
    {
    	OfficeUser::checkAccess("permissions:update");
        $row = OfficePermission::find($id);
        if(!$row)
            return redirect()->route("office.permissions")->withErrors(["msg" => __("Rekord nie istnieje")]);
        
        $validated = $request->validated();

        $row->name = $validated["name"];
        $row->role = $validated["role"];
        $row->admin_permission_type = $validated["role"] == OfficePermission::ROLE_ADMIN ? $validated["admin_permission_type"] : null;
        if($row->role == OfficePermission::ROLE_ADMIN && $row->admin_permission_type == OfficePermission::ADMIN_PERMISSION_TYPE_FULL)
            $row->permissions = "";
        else
            $row->permissions = implode(";", $validated["permissions"][$validated["role"]]);
        
        $row->save();

        Helper::setMessage("permissions", __("Grupa została zaktualizowana"));
        if($this->isApply())
            return redirect()->route("office.permission.update", $row->id);
        else
            return redirect()->route("office.permissions");
    }

    public function delete(Request $request, $id)
    {
    	OfficeUser::checkAccess("permissions:delete");

        $row = OfficePermission::find($id);
        if(!$row)
            return redirect()->route("office.permissions")->withErrors(["msg" => __("Rekord nie istnieje")]);

        if(!$row->canDelete())
            return redirect()->route("office.permissions")->withErrors(["msg" => __("Nie można usunąć grupy do której przypisani są użytkownicy")]);

        $row->delete();

        Helper::setMessage("permissions", __("Grupa została usunięta"));
        return redirect()->route("office.permissions");
    }
}
