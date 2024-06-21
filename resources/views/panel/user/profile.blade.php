@extends("panel.layout")
  
@section("title"){{ __("Mój profil") }}@endsection 
 
@section("scripts")
    <script src="{{ a("/res/panel/js/profile.js") }}"></script>
@endsection
 
@section("content")
    <h4>{{ __("Mój profil") }}</h4>
    
    <form method="post" class="validate">
        @include("panel.partials.message", ["module" => "profile"])
        @include("panel.partials.errors")
        
        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <label for="registerFirstName" class="form-label">{{ __("Imię") }}</label>
                <input type="text" name="firstname" class="form-control" value="{{ $user["firstname"] ?? "" }}" id="registerFirstName" data-validate="required">
                <small class="input-error-info"></small>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="registerLastName" class="form-label">{{ __("Nazwisko") }}</label>
                <input type="text" name="lastname" class="form-control" value="{{ $user["lastname"] ?? "" }}" id="registerLastName" data-validate="required">
                <small class="input-error-info"></small>
            </div>
            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input" name="change_password" type="checkbox" value="1" id="changePassword" onclick="Profile.changePassword(this)" @if(old('change_password', false)){{ "checked" }}@endif>
                    <label class="form-check-label" for="changePassword">
                        {{ __("Zmien hasło") }}
                    </label>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3 profile-change-password @if(!old('change_password', false)){{ "d-none" }}@endif">
                <label for="registerPassword" class="form-label">{{ __("Hasło") }}*</label>
                <input type="password" name="password" class="form-control" id="registerPassword" data-validate="required|password">
                <small class="input-error-info"></small>
            </div>
            <div class="col-12 col-md-6 mb-3 profile-change-password @if(!old('change_password', false)){{ "d-none" }}@endif">
                <label for="registerPassword2" class="form-label">{{ __("Powtórz hasło") }}*</label>
                <input type="password" name="password_2" class="form-control" id="registerPassword2" data-validate="required|same:password">
                <small class="input-error-info"></small>
            </div>
        </div>
            
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <button type="submit" class="btn btn-primary">{{ __("Zapisz") }}</button>
    </form>
@endsection