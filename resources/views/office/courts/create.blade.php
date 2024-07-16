@extends("office.layout-auth")

@section("title"){{ "Nowy sąd" }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.courts") }}">{{ __("Baza sądów") }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Nowy sąd") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")

    <form method="POST" action="{{ route("office.court.create.post") }}" class="validate">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                @include("office.courts._form")
            </div>
            <div class="card-footer">
                @include("office.partials.buttons", ["back" => route("office.courts")])
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection
