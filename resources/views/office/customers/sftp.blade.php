@extends("office.layout-auth")

@section("title"){{ $customer->name }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.customers") }}">{{ __("Klienci") }}</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.customer.show", $customer->id) }}">{{ $customer->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Konfiguracja SFTP") }}</li>
@endsection

@section("scripts")
    <script src="{{ a("/res/office/js/modules/customer.js") }}"></script>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:customers"])
    
    <form method="POST" action="{{ route("office.customer.sftp.post", $customer->id) }}" class="validate">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-4 mb-3">
                        <label for="formHost" class="form-label">{{ __("Adres serwera") }}</label>
                        <input type="text" id="formHost" name="host" class="form-control" value="{{ $form["host"] ?? "" }}">
                    </div>
                        
                    <div class="col-12 col-sm-2 mb-3">
                        <label for="formPort" class="form-label">{{ __("Port") }}</label>
                        <input type="text" id="formPort" name="port" class="form-control" value="{{ $form["port"] ?? "" }}">
                    </div>
                        
                    <div class="col-12 col-sm-3 mb-3">
                        <label for="formLogin" class="form-label">{{ __("Login") }}</label>
                        <input type="text" id="formLogin" name="login" class="form-control" value="{{ $form["login"] ?? "" }}">
                    </div>
                        
                    <div class="col-12 col-sm-3 mb-3">
                        <label for="formPassword" class="form-label">{{ __("Hasło") }}</label>
                        @if(!empty($form["password"]))
                            <div>
                                <button type="button" class="btn btn-danger w-100" onclick="Customer.changeSftpPassword(this)">{{ __("Zmień hasło") }}</button>
                                <input type="password" id="formPassword" name="password" class="form-control d-none" value="{{ $form["password"] ?? "" }}">
                            </div>
                        @else
                            <input type="password" id="formPassword" name="password" class="form-control" value="{{ $form["password"] ?? "" }}">
                            <input type="hidden" name="set_password" class="form-control" value="1">
                        @endif
                    </div>
                        
                    <div class="col-12 col-sm-4 mb-3">
                        <label for="formPath" class="form-label">{{ __("Ścieżka") }}</label>
                        <input type="text" id="formPath" name="path" class="form-control" value="{{ $form["path"] ?? "" }}">
                    </div>
                        
                    <div class="col-12 col-sm-4 mb-3">
                        <label class="form-label d-block">{{ __("Typ transferu") }}</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="transfer_type" value="active" id="formTransferTypeActive" @if(($form["transfer_type"] ?? "") == "active"){{ "checked" }}@endif>
                            <label class="form-check-label" for="formTransferTypeActive">{{ __("Aktywny") }}</label>
                        </div>    
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="transfer_type" value="passive" id="formTransferTypePassive" @if(($form["transfer_type"] ?? "passive") == "passive"){{ "checked" }}@endif>
                            <label class="form-check-label" for="formTransferTypePassive">{{ __("Pasywny") }}</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 mb-3">
                        <label for="formSsl" class="form-label">{{ __("Połączenie szyfrowane SSL") }}</label>
                        <div class="form-check">
                            <input class="form-check-input" id="formSsl" type="checkbox" name="ssl" value="1" @if(($form["ssl"] ?? "")){{ "checked" }}@endif>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col">
                        <button type="button" class="btn btn-sm btn-info" onclick="Customer.sftpTestConfiguration(this, '{{ route("office.ajax", "sftpTestConfiguration", false) }}');">{{ __("Przetestuj konfigurację") }}</button>
                        <span class="d-none ms-2 text-muted" id="configuration-progress">{{ __("Testowanie połączenia...") }}</span>

                        <div class="alert alert-success mt-2 d-none" role="alert" id="configuration-valid">
                            {{ __("Konfiguracja poprawna!") }}
                        </div>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="configuration-invalid">
                            {{ __("Konfiguracja niepoprawna.") }} <span id="configuration-invalid-error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @include("office.partials.buttons", ["back" => route("office.customer.show", $customer->id), "hideSaveAndEdit" => true])
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection