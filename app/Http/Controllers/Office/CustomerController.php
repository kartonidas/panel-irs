<?php
 
namespace App\Http\Controllers\Office;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 
use Illuminate\View\View;

use App\Http\Requests\Office\CustomerSftpRequest;
use App\Http\Requests\Office\CustomerStoreRequest;
use App\Http\Requests\Office\CustomerUpdateRequest;
use App\Http\Requests\Office\CustomerUserStoreRequest;
use App\Http\Requests\Office\CustomerUserUpdateRequest;
use App\Http\Requests\Office\CustomerVisibilityFieldsRequest;
use App\Libraries\Data;
use App\Libraries\Helper;
use App\Models\Customer;
use App\Models\OfficeUser;
use App\Models\User;
use App\Traits\Form;

class CustomerController
{
    use Form;
    
    public function list(Request $request)
    {
        OfficeUser::checkAccess("customers:list");
        view()->share("activeMenuItem", "customers");
        
        $filter = Helper::getFilter($request, "office:customers");
        
        $customers = Customer::orderBy("name", "ASC");
        if(!empty($filter["name"]))
            $customers->searchName($filter["name"]);
        if(!empty($filter["nip_regon_krs"]))
        {
            $customers->where(function($q) use($filter) {
                $q->where("nip", "LIKE", "%" . $filter["nip_regon_krs"] . "%");
                $q->orWhere("regon", "LIKE", "%" . $filter["nip_regon_krs"] . "%");
                $q->orWhere("krs", "LIKE", "%" . $filter["nip_regon_krs"] . "%");
            });
        }
        
        $customers = $customers->paginate(config("office.lists.size"));
        
        $vData = [
            "filter" => $filter,
            "customers" => $customers,
        ];
        return view("office.customers.list", $vData);
    }
    
    public function customerCreate(Request $request)
    {
        OfficeUser::checkAccess("customers:create");
        view()->share("activeMenuItem", "customers");
        
        $formData = [
            "active" => 1
        ];

        $vData = [
            "form" => $request->old() ? $request->old() : $formData,
        ];
        return view("office.customers.create", $vData);
    }
    
    public function customerCreatePost(CustomerStoreRequest $request)
    {
        OfficeUser::checkAccess("customers:create");
        
        $validated = $request->validated();
        
        $customer = DB::transaction(function () use($validated) {
            $customer = new Customer;
            $customer->name = $validated["name"];
            $customer->street = $validated["street"];
            $customer->house_no = $validated["house_no"];
            $customer->apartment_no = $validated["apartment_no"];
            $customer->city = $validated["city"];
            $customer->zip = $validated["zip"];
            $customer->nip = $validated["nip"];
            $customer->regon = $validated["regon"];
            $customer->krs = $validated["krs"];
            $customer->active = !empty($validated["active"]) ? 1 : 0;
            $customer->save();
            
            $customer->assignCaseNumbers($validated["case_numbers"]);
            
            return $customer;
        });

        Helper::setMessage("office:customers", __("Klient został dodany"));
        if($this->isApply())
            return redirect()->route("office.customer.update", $customer->id);
        else
            return redirect()->route("office.customers");
    }
    
    public function customerUpdate(Request $request, $id)
    {
        OfficeUser::checkAccess("customers:update");
        view()->share("activeMenuItem", "customers");
        
        $customer = Customer::find($id);
        if(!$customer)
            return redirect()->route("office.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $formData = $customer->toArray();
        $formData["case_numbers"] = implode(PHP_EOL, $customer->getAssignedCaseNumbers());
        
        $vData = [
            "id" => $customer->id,
            "form" => $request->old() ? $request->old() : $formData,
            "customer" => $customer,
        ];
        
        return view("office.customers.update", $vData);
    }
    
    public function customerUpdatePost(CustomerUpdateRequest $request, $id)
    {
        OfficeUser::checkAccess("customers:update");
        
        $customer = Customer::find($id);
        if(!$customer)
            return redirect()->route("office.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $validated = $request->validated();
        
        DB::transaction(function () use($customer, $validated) {
            $customer->name = $validated["name"];
            $customer->street = $validated["street"];
            $customer->house_no = $validated["house_no"];
            $customer->apartment_no = $validated["apartment_no"];
            $customer->city = $validated["city"];
            $customer->zip = $validated["zip"];
            $customer->nip = $validated["nip"];
            $customer->regon = $validated["regon"];
            $customer->krs = $validated["krs"];
            $customer->active = !empty($validated["active"]) ? 1 : 0;
            $customer->save();
            
            $customer->assignCaseNumbers($validated["case_numbers"]);
        });

        Helper::setMessage("office:customers", __("Klient został zaktualizowany"));
        if($this->isApply())
            return redirect()->route("office.customer.update", $customer->id);
        else
            return redirect()->route("office.customer.show", $customer->id);
    }
    
    public function customerShow(Request $request, $id)
    {
        OfficeUser::checkAccess("customers:list");
        view()->share("activeMenuItem", "customers");
        
        $customer = Customer::find($id);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $vData = [
            "customer" => $customer,
            "customerVisibilityFields" => $customer->getVisibilityFields(),
            "fieldsVisibility" => Data::getFieldsVisibility(),
            "users" => $customer->users()->orderBy("lastname", "ASC")->orderBy("firstname", "ASC")->get(),
            "sftp" => $customer->sftp()->first(),
        ];
        
        return view("office.customers.show", $vData);
    }
    
    public function customerDelete(Request $request, $id)
    {
        OfficeUser::checkAccess("customers:delete");

        $customer = Customer::find($id);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);

        $customer->delete();
        
        Helper::setMessage("office:customers", __("Klient został usunięty"));
        return redirect()->route("office.customers");
    }
    
    public function customerUserCreate(Request $request, $customerId)
    {
        OfficeUser::checkAccess("customers:update");
        view()->share("activeMenuItem", "customers");
        
        $customer = Customer::find($customerId);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $formData = [
            "active" => 1
        ];

        $vData = [
            "form" => $request->old() ? $request->old() : $formData,
            "customer" => $customer,
        ];
        return view("office.customers.users.create", $vData);
    }
    
    public function customerUserCreatePost(CustomerUserStoreRequest $request, $customerId)
    {
        OfficeUser::checkAccess("customers:update");
        
        $customer = Customer::find($customerId);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $validated = $request->validated();
        
        $user = new User;
        $user->customer_id = $customerId;
        $user->firstname = $validated["firstname"];
        $user->lastname = $validated["lastname"];
        $user->email = $validated["email"];
        $user->password = Hash::make($validated["password"]);
        $user->active = !empty($validated["active"]) ? 1 : 0;
        $user->save();

        Helper::setMessage("office:customers", __("Użytkownik został dodany"));
        if($this->isApply())
            return redirect()->route("office.customer.user.update", [$customerId, $user->id]);
        else
            return redirect()->route("office.customer.show", $customerId);
    }
    
    public function customerUserUpdate(Request $request, $customerId, $id)
    {
        OfficeUser::checkAccess("customers:update");
        view()->share("activeMenuItem", "customers");
        
        $customer = Customer::find($customerId);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $user = User::find($id);
        if(!$user)
            return redirect()->route("ofice.customer.show", $customerId)->withErrors(["msg" => __("Użytkownik nie istnieje")]);
        
        $vData = [
            "id" => $id,
            "form" => $request->old() ? $request->old() : $user->toArray(),
            "customer" => $customer,
        ];
        
        return view("office.customers.users.update", $vData);
    }
    
    public function customerUserUpdatePost(CustomerUserUpdateRequest $request, $customerId, $id)
    {
        OfficeUser::checkAccess("customers:update");
        
        $customer = Customer::find($customerId);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $user = User::find($id);
        if(!$user)
            return redirect()->route("ofice.customer.show", $customerId)->withErrors(["msg" => __("Użytkownik nie istnieje")]);
        
        $validated = $request->validated();
        $user->firstname = $validated["firstname"];
        $user->lastname = $validated["lastname"];
        $user->email = $validated["email"];
        if(!empty($validated["change_password"]))
            $user->password = Hash::make($validated["password"]);
        $user->active = !empty($validated["active"]) ? 1 : 0;
        $user->save();
        
        Helper::setMessage("office:customers", __("Użytkownik został dodany"));
        if($this->isApply())
            return redirect()->route("office.customer.user.update", [$customerId, $user->id]);
        else
            return redirect()->route("office.customer.show", $customerId);
    }
    
    public function customerUserDelete(Request $request, $customerId, $id)
    {
        OfficeUser::checkAccess("customers:update");

        $customer = Customer::find($customerId);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $user = User::find($id);
        if(!$user)
            return redirect()->route("ofice.customer.show", $customerId)->withErrors(["msg" => __("Użytkownik nie istnieje")]);

        $user->delete();
        
        Helper::setMessage("office:customers", __("Użytkownik został usunięty"));
        return redirect()->route("office.customer.show", $customerId);
    }
    
    public function customerUserBlockAccount(Request $request, $customerId, $id)
    {
        OfficeUser::checkAccess("customers:update");
        
        $customer = Customer::find($customerId);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $user = User::find($id);
        if(!$user)
            return redirect()->route("ofice.customer.show", $customerId)->withErrors(["msg" => __("Konto nie istnieje")]);
        
        if($user->block)
            return redirect()->route("ofice.customer.show", $customerId)->withErrors(["msg" => __("Konto aktualnie jest zablokowane")]);
        
        $user->block = 1;
        $user->block_reason = Data::USER_BLOCK_REASON_ADMIN;
        $user->save();
        
        Helper::setMessage("office:customers", __("Konto zostało zablokowane"));
        return redirect()->route("office.customer.show", $customerId);
    }
    
    public function customerUserUnblockAccount(Request $request, $customerId, $id)
    {
        OfficeUser::checkAccess("customers:update");
        
        $customer = Customer::find($customerId);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $user = User::find($id);
        if(!$user)
            return redirect()->route("ofice.customer.show", $customerId)->withErrors(["msg" => __("Konto nie istnieje")]);
        
        if(!$user->block)
            return redirect()->route("ofice.customer.show", $customerId)->withErrors(["msg" => __("Konto nie jest aktualnie zablokowane")]);
        
        $user->block = 0;
        $user->block_reason = null;
        $user->save();
        
        Helper::setMessage("office:customers", __("Konto zostało odblokowane"));
        return redirect()->route("office.customer.show", $customerId);
    }
    
    public function customerSftp(Request $request, $id)
    {
        OfficeUser::checkAccess("customers:update");
        view()->share("activeMenuItem", "customers");
        
        $customer = Customer::find($id);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $config = $customer->sftp()->first();
        
        $vData = [
            "id" => $id,
            "customer" => $customer,
            "form" => $config?->toArray(),
        ];
        return view("office.customers.sftp", $vData);
    }
    
    public function customerSftpPost(CustomerSftpRequest $request, $id)
    {
        OfficeUser::checkAccess("customers:update");
        
        $customer = Customer::find($id);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $validated = $request->validated();
        
        $sftpConfig = $customer->ensureSftpConfigRow();
        $sftpConfig->host = $validated["host"];
        $sftpConfig->port = $validated["port"];
        $sftpConfig->login = $validated["login"];
        
        if(!empty($validated["set_password"]))
            $sftpConfig->password = !empty($validated["password"]) ? Crypt::encryptString($validated["password"]) : null;
            
        $sftpConfig->path = $validated["path"];
        $sftpConfig->transfer_type = $validated["transfer_type"];
        $sftpConfig->ssl = $validated["ssl"] ?? 0;
        $sftpConfig->save();
        
        Helper::setMessage("office:customers", __("Konfiguracja została zapisana"));
        return redirect()->route("office.customer.sftp", $id);
    }
    
    public function customerVisibilityElements(Request $request, $id)
    {
        OfficeUser::checkAccess("customers:update");
        
        $customer = Customer::find($id);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $vData = [
            "customer" => $customer,
            "fieldsVisibility" => Data::getFieldsVisibility(),
            "customerVisibilityFields" => $customer->getVisibilityFields(),
        ];
        return view("office.customers.visibility-elements", $vData);
    }
    
    public function customerVisibilityElementsPost(CustomerVisibilityFieldsRequest $request, $id)
    {
         OfficeUser::checkAccess("customers:update");
        
        $customer = Customer::find($id);
        if(!$customer)
            return redirect()->route("ofice.customers")->withErrors(["msg" => __("Klient nie istnieje")]);
        
        $validated = $request->validated();
        $customer->saveVisibilityFields($validated["visibility"] ?? []);
        
        Helper::setMessage("office:customers", __("Konfiguracja została zapisana"));
        return redirect()->route("office.customer.visibility-elements", $id);
    }
}