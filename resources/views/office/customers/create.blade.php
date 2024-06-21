@extends("office.layout-auth")

@section("title"){{ "Nowa klient" }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.customers") }}">{{ __("Klienci") }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Nowy klient") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")

    <form method="POST" action="{{ route("office.customer.create.post") }}" class="validate">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                @include("office.customers._form")
            </div>
            <div class="card-footer">
                @include("office.partials.buttons", ["back" => route("office.customers")])
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection
