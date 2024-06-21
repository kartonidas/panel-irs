<div class="row">
    <div class="col-12 col-md-6 mb-3">
        <label for="formUserStreet" class="form-label">{{ __("Adres e-mail") }}*</label>
        <input type="text" id="formUserStreet" name="user[email]" class="form-control" value="{{ $form["user"]["email"] ?? "" }}" data-validate="required|email">
        <small class="input-error-info"></small>
    </div>
        
    <div class="col-12 col-md-6 mb-3">
        <label for="formUserName" class="form-label">{{ __("Nazwa") }}*</label>
        <input type="text" id="formUserName" name="user[name]" class="form-control" value="{{ $form["user"]["name"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
        
    <div class="col-12 mb-3">
        <label for="formUserPermission" class="form-label">{{ __("Grupa uprawnień") }}*</label>
        <select class="form-select" name="user[office_permission_id]" id="formUserPermission" data-validate="required">
            <option></option>
            @foreach($permissions as $permission)
                <option value="{{ $permission->id }}" @if(($form["user"]["office_permission_id"] ?? "") == $permission->id){{ "selected" }}@endif>{{ $permission->name }}</option>
            @endforeach
        </select>
        <small class="input-error-info"></small>
    </div>
        
    @if(!empty($id))
        <div class="col-12">
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="user[change_password]" value="1" id="formUserChangePassword" @if(($form["user"]["change_password"] ?? "")){{ "checked" }}@endif onclick="User.changePassword(this)">
                <label class="form-check-label" for="formUserChangePassword">
                    {{ __("Zmień hasło") }}
                </label>
            </div>
        </div>
    @endif

    <div class="col-12 col-sm-6 mb-3 password @if(!empty($id) && !($form["user"]["change_password"] ?? "")){{ "d-none" }}@endif">
        <label for="formUserPassword" class="form-label">{{ __("Hasło") }}*</label>
        <input type="password" id="formUserPassword" name="user[password]" class="form-control" value="" data-validate="required|password|same:user[password_2]" autocomplete="off">
        <small class="input-error-info"></small>
    </div>

    <div class="col-12 col-sm-6 mb-3 password @if(!empty($id) && !($form["user"]["change_password"] ?? "")){{ "d-none" }}@endif">
        <label for="formUserPassword2" class="form-label">{{ __("Powtórz hasło") }}*</label>
        <input type="password" id="formUserPassword2" name="user[password_2]" class="form-control" value="" data-validate="required|password" autocomplete="off">
        <small class="input-error-info"></small>
    </div>
        
    <div class="col-12">
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="user[active]" value="1" id="formUserActive" @if($form["user"]["active"] ?? ""){{ "checked" }}@endif>
            <label class="form-check-label" for="formUserActive">
                {{ __("Aktywny") }}
            </label>
        </div>
    </div>
</div>
    