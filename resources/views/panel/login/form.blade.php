@extends("panel.layout")

@section("title"){{ __("Logowanie") }}@endsection

@section("content")
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __("Zaloguj się") }}</p>
                <form action="{{ route("login.post") }}" method="post" class="validate">
                    @include("office.partials.errors")
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="email" name="email" class="form-control" value="{{ old("email") }}" placeholder="{{ __("Adres e-mail") }}" data-validate="required|email">
                            <div class="input-group-text">
                                <span class="bi bi-envelope"></span>
                            </div>
                        </div>
                        <small class="input-error-info"></small>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" placeholder="{{ __("Hasło") }}" data-validate="required">
                            <div class="input-group-text">
                                <span class="bi bi-lock-fill"></span>
                            </div>
                        </div>
                        <small class="input-error-info"></small>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">{{ __("Zaloguj się") }}</button>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection