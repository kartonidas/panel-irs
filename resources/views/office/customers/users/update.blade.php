@extends("office.layout-auth")

@section("title"){{ $customer->name }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.customers") }}">{{ __("Klienci") }}</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.customer.show", $customer->id) }}">{{ $customer->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Aktualizacja użytkownika klienta") }}</li>
@endsection

@section("scripts")
    <script src="{{ a("/res/office/js/modules/customer.js") }}"></script>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:customers"])

    <form method="POST" action="{{ route("office.customer.user.update.post", [$customer->id, $id]) }}" class="validate">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                @include("office.customers.users._form")
            </div>
            <div class="card-footer">
                @include("office.partials.buttons", ["back" => route("office.customer.show", $customer->id)])
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection
