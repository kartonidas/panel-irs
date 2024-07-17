<?php

namespace App\Http\Controllers\Office;

use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Http\Requests\Office\OfficeUserStoreRequest;
use App\Http\Requests\Office\OfficeUserUpdateRequest;
use App\Http\Requests\Office\ProfileUpdateRequest;
use App\Libraries\Data;
use App\Libraries\Helper;
use App\Models\Customer;
use App\Models\OfficeUser;
use App\Models\OfficeUsersCaseAccess;
use App\Models\OfficePermission;
use App\Traits\Form;

class OfficeUserController extends Controller
{
    use Form;
    
    protected function modelName() : string
    {
        return OfficeUser::class;
    }
    
    public function list(Request $request)
    {
        OfficeUser::checkAccess("users:list");
        view()->share("activeMenuItem", "users");
        
        $sort = $this->getSortOrder($request);
        $filter = $this->getFilter($request);

        $rows = OfficeUser::orderBy($sort[0], $sort[1]);
        if(!empty($filter["name"]))
            $rows->where("name", "LIKE", "%" . $filter["name"] . "%");
        if(!empty($filter["email"]))
            $rows->where("email", "LIKE", "%" . $filter["email"] . "%");
        if(isset($filter["block"]))
        {
            if($filter["block"] == "1")
                $rows->where("block", 1);
            elseif($filter["block"] == "0")
                $rows->where("block", 0);
        }

        $rows = $rows->paginate(config("office.lists.size"));

        $vData = [
            "users" => $rows,
            "filter" => $filter,
            "permissions" => OfficePermission::pluck("name", "id")->all(),
            "sort" => $sort,
            "sortColumns" => $this->getSortableFields($sort),
        ];
        return view("office.users.list", $vData);
    }

    public function userCreate(Request $request)
    {
        OfficeUser::checkAccess("users:create");
        view()->share("activeMenuItem", "users");
        
        $formData = [
            "user" => [
                "active" => 1
            ],
        ];

        $old = \Request::old();
        if($old)
            $formData = array_merge($formData, $old);

        $vData = [
            "form" => $formData,
            "permissions" => OfficePermission::orderBy("role", "ASC")->orderBy("name", "ASC")->get(),
            "caseAccessTypes" => OfficeUser::getCaseAccessTypes(),
            "canSelectCaseAccess" => false,
        ];
        return view("office.users.create", $vData);
    }

    public function userCreateSave(OfficeUserStoreRequest $request)
    {
        OfficeUser::checkAccess("users:create");
        $data = $request->validated();

        $row = new OfficeUser;
        $row->name = $data["user"]["name"];
        $row->email = $data["user"]["email"];
        $row->password = Hash::make($data["user"]["password"]);
        $row->active = !empty($data["user"]["active"]) ? 1 : 0;
        $row->office_permission_id = $data["user"]["office_permission_id"];
        $row->case_access_type = $data["user"]["case_access_type"];
        $row->save();

        Helper::setMessage("office:users", __("Pracownik został dodany"));
        if($this->isApply())
            return redirect()->route("office.user.update", $row->id);
        else
            return redirect()->route("office.users");
    }

    public function userUpdate(Request $request, $id)
    {
        OfficeUser::checkAccess("users:update");
        view()->share("activeMenuItem", "users");
        
        $row = OfficeUser::find($id);
        if(!$row)
            return redirect()->route("office.users")->withErrors(["msg" => __("Pracownik nie istnieje")]);

        $formData = [
            "user" => $row->toArray(),
        ];

        $old = \Request::old();
        if($old)
            $formData = array_merge($formData, $old);

        $vData = [
            "id" => $row->id,
            "form" => $formData,
            "permissions" => OfficePermission::orderBy("role", "ASC")->orderBy("name", "ASC")->get(),
            "caseAccessTypes" => OfficeUser::getCaseAccessTypes(),
            "canSelectCaseAccess" => $row->case_access_type == OfficeUser::CASE_ACCESS_SELECTED,
        ];
        return view("office.users.update", $vData);
    }

    public function userUpdateSave(OfficeUserUpdateRequest $request, $id)
    {
        OfficeUser::checkAccess("users:update");
        $row = OfficeUser::find($id);
        if(!$row)
            return redirect()->route("office.users")->withErrors(["msg" => __("Pracownik nie istnieje")]);
        
        $data = $request->validated();

        $row->name = $data["user"]["name"];
        $row->email = $data["user"]["email"];
        $row->active = !empty($data["user"]["active"]) ? 1 : 0;
        $row->office_permission_id = $data["user"]["office_permission_id"];
        $row->case_access_type = $data["user"]["case_access_type"];
        if(!empty($data["user"]["change_password"]))
            $row->password = Hash::make($data["user"]["password"]);
        $row->save();

        Helper::setMessage("office:users", __("Pracownik został zaktualizowany"));
        if($this->isApply())
            return redirect()->route("office.user.update", $row->id);
        else
            return redirect()->route("office.users");
    }

    public function userDelete(Request $request, $id)
    {
        OfficeUser::checkAccess("users:delete");
        $row = OfficeUser::find($id);
        if(!$row)
            return redirect()->route("ofice.users")->withErrors(["msg" => __("Pracownik nie istnieje")]);

        $row->delete();

        Helper::setMessage("office:users", __("Pracownik został usunięty"));
        return redirect()->route("office.users");
    }
    
    public function profile(Request $request)
    {
        view()->share("activeMenuItem", "profile");
        
        $row = Auth::guard("office")->user();

        $formData = [
            "user" => $row
        ];

        $old = \Request::old();
        if($old)
            $formData = array_merge($formData, $old);

        $vData = [
            "form" => $formData
        ];

        return view("office.users.profile", $vData);
    }

    public function profilePost(ProfileUpdateRequest $request)
    {
        $row = Auth::guard("office")->user();

        $data = $request->validated();
        
        if(!empty($data["user"]["change_password"]))
        {
            if(!Hash::check($data["user"]["current_password"], $row->password))
                return redirect()->route("office.profile")->withErrors(["msg" => __("Podane aktualne hasło jest nieprawidłowe")])->withInput();

            $row->password = Hash::make($data["user"]["password"]);
        }

        $row->name = $data["user"]["name"];
        $row->email = $data["user"]["email"];
        $row->save();

        Helper::setMessage("office:adminprofile", __("Profil został zaktualizowany"));
        return redirect()->route("office.profile");
    }
    
    public function userBlockAccount(Request $request, $id)
    {
        OfficeUser::checkAccess("users:update");
        $row = OfficeUser::find($id);
        if(!$row)
            return redirect()->route("office.users")->withErrors(["msg" => __("Pracownik nie istnieje")]);
        
        if($row->block)
            return redirect()->route("office.users")->withErrors(["msg" => __("Wybrane konto aktualnie jest zablokowane")]);
        
        $row->block = 1;
        $row->block_reason = Data::USER_BLOCK_REASON_ADMIN;
        $row->save();
        
        Helper::setMessage("office:users", __("Konto zostało zablokowane"));
        return redirect()->route("office.users");
    }
    
    public function userUnblockAccount(Request $request, $id)
    {
        OfficeUser::checkAccess("users:update");
        $row = OfficeUser::find($id);
        if(!$row)
            return redirect()->route("office.users")->withErrors(["msg" => __("Pracownik nie istnieje")]);
        
        if(!$row->block)
            return redirect()->route("office.users")->withErrors(["msg" => __("Wybrane konto nie jest aktualnie zablokowane")]);
        
        $row->block = 0;
        $row->block_reason = null;
        $row->save();
        
        Helper::setMessage("office:users", __("Konto zostało odblokowane"));
        return redirect()->route("office.users");
    }
    
    public function userSelectedCaseAccess(Request $request, $id)
    {
        OfficeUser::checkAccess("users:update");
        view()->share("activeMenuItem", "users");
        
        $row = OfficeUser::find($id);
        if(!$row)
            return redirect()->route("office.users")->withErrors(["msg" => __("Pracownik nie istnieje")]);
        
        if($row->case_access_type != OfficeUser::CASE_ACCESS_SELECTED)
            return redirect()->route("office.users")->withErrors(["msg" => __("Brak zdefiniowanego dostęp do wybranych spraw")]);
        
        $customersArray = [];
        $customers = Customer::orderBy("name", "ASC")->get();
        foreach($customers as $customer)
        {
            $customersArray[$customer->id] = [
                "name" => $customer->name,
                "case_numbers" => $customer->caseNumbers()->pluck("number")->all(),
            ];
        }
        
        $vData = [
            "user" => $row,
            "caseSelectedAccess" => $row->caseAccess()->paginate(config("office.lists.ajax.size")),
            "customers" => $customersArray,
            "caseAccessTypes" => OfficeUsersCaseAccess::getCaseAccessTypes()
        ];

        return view("office.users.selected_case_access_form", $vData);
    }
}