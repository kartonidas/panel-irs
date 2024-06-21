@extends("office.layout-auth")

@section("title"){{ __("Edycja pracownika")  }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.users") }}">{{ __("Pracownicy kancelarii") }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Edycja pracownika") }}</li>
@endsection

@section("scripts")
    <script src="/res/office/js/modules/user.js"></script>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:users"])

    <form method="POST" action="{{ route("office.user.update.post", $id) }}" class="validate">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                @include("office.users._form")
            </div>
            <div class="card-footer">
                @include("office.partials.buttons", ["back" => route("office.users")])
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection
