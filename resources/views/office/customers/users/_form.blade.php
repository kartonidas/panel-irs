<div class="row">
    <div class="col-12 col-md-4 mb-3">
        <label for="userFirstName" class="form-label">{{ __("Imię") }}*</label>
        <input type="text" name="firstname" class="form-control" value="{{ $form["firstname"] ?? "" }}" id="userFirstName" data-validate="required">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-md-4 mb-3">
        <label for="userLastName" class="form-label">{{ __("Nazwisko") }}*</label>
        <input type="text" name="lastname" class="form-control" value="{{ $form["lastname"] ?? "" }}" id="userLastName" data-validate="required">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-sm-4 mb-3">
        <label for="userEmail" class="form-label">{{ __("Email użytkownika (login)") }}*</label>
        <input type="text" name="email" class="form-control" value="{{ $form["email"] ?? "" }}" id="userEmail" data-validate="required|email">
        <small class="input-error-info"></small>
    </div>
    
    @if(!empty($id))
        <div class="col-12 mb-3">
            <div class="form-check">
                <input class="form-check-input" name="change_password" type="checkbox" value="1" id="changePassword" onclick="Customer.changePassword(this)" @if(old('change_password', false)){{ "checked" }}@endif>
                <label class="form-check-label" for="changePassword">
                    {{ __("Zmien hasło") }}
                </label>
            </div>
        </div>
    @endif
    
    <div class="col-12 col-md-6 mb-3 user-change-password @if(!empty($id) && !old('change_password', false)){{ "d-none" }}@endif">
        <label for="userPassword" class="form-label">{{ __("Hasło") }}*</label>
        <input type="password" name="password" class="form-control" id="userPassword" data-validate="required|password">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-md-6 mb-3 user-change-password @if(!empty($id) && !old('change_password', false)){{ "d-none" }}@endif">
        <label for="userPassword2" class="form-label">{{ __("Powtórz hasło") }}*</label>
        <input type="password" name="password_2" class="form-control" id="userPassword2" data-validate="required|same:password">
        <small class="input-error-info"></small>
    </div>
        
    <div class="col-12 mb-1">
        <div class="form-check">
            <input class="form-check-input" name="active" type="checkbox" value="1" id="userActive" @if($form["active"] ?? false){{ "checked" }}@endif>
            <label class="form-check-label" for="userActive">
                {{ __("Konto aktywne") }}
            </label>
        </div>
    </div>
</div>