@extends("office.layout-auth")

@section("title"){{ "Nowa grupa" }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.permissions") }}">{{ __("Grupy uprawnień") }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Nowa grupa") }}</li>
@endsection

@section("scripts")
    <script src="{{ a("/res/office/js/modules/permission.js") }}"></script>
@endsection

@section("content")
    @include("office.partials.errors")

    <form method="POST" action="{{ route("office.permission.create.post") }}" class="validate">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                @include("office.permissions._form")
            </div>
            <div class="card-footer">
                @include("office.partials.buttons", ["back" => route("office.permissions")])
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection
