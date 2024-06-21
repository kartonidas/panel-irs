@extends("office.layout-auth")

@section("title"){{ __("Profil") }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item active" aria-current="page">{{ __("Profil") }}</li>
@endsection

@section("scripts")
    <script src="/res/office/js/modules/user.js"></script>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:adminprofile"])

    <form method="POST" action="{{ route("office.profile.post") }}" class="validate">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="formUserEmail" class="form-label">{{ __("Adres e-mail") }}*</label>
                        <input type="text" id="formUserEmail" name="user[email]" class="form-control" value="{{ $form["user"]["email"] }}" data-validate="required|email">
                        <small class="input-error-info"></small>
                    </div>
        
                    <div class="col-12 col-md-6 mb-3">
                        <label for="formUserName" class="form-label">{{ __("Nazwa") }}*</label>
                        <input type="text" id="formUserName" name="user[name]" class="form-control" value="{{ $form["user"]["name"] }}" data-validate="required">
                        <small class="input-error-info"></small>
                    </div>
                
                    <div class="col-12">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="user[change_password]" value="1" id="formUserChangePassword" @if(!empty($form["user"]["change_password"])){{ "checked" }}@endif onclick="User.changePassword(this)">
                            <label class="form-check-label" for="formUserChangePassword">
                                {{ __("Zmień hasło") }}
                            </label>
                        </div>
                    </div>
            
                    <div class="col-12 col-md-4 mb-3 password @if(empty($form["user"]["change_password"])){{ "d-none " }}@endif">
                        <label for="formUserCurrentPassword" class="form-label">{{ __("Aktualne hasło") }}*</label>
                        <input type="password" id="formUserCurrentPassword" name="user[current_password]" class="form-control" value="" autocomplete="off" data-validate="required">
                        <small class="input-error-info"></small>
                    </div>
        
                    <div class="col-12 col-md-4 mb-3 password @if(empty($form["user"]["change_password"])){{ "d-none " }}@endif">
                        <label for="formUserPassword" class="form-label">{{ _("Hasło") }}*</label>
                        <input type="password" id="formUserPassword" name="user[password]" class="form-control" value="" autocomplete="off" data-validate="required|password|same:user[password_2]">
                        <small class="input-error-info"></small>
                    </div>
        
                    <div class="col-12 col-md-4 mb-3 password @if(empty($form["user"]["change_password"])){{ "d-none " }}@endif">
                        <label for="formUserPassword2" class="form-label">{{ __("Powtórz hasło") }}*</label>
                        <input type="password" id="formUserPassword2" name="user[password_2]" class="form-control" value="" autocomplete="off" data-validate="required">
                        <small class="input-error-info"></small>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">{{ __("Zapisz") }}</button>
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection