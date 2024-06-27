@extends("office.layout-auth")

@section("title"){{ "Nowa wartość słownikowa" }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.dictionaries") }}">{{ __("Słowniki") }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Nowa wartość słownikowa") }}</li>
@endsection

@section("scripts")
    <script src="{{ a("/res/office/js/modules/dictionary.js") }}"></script>
@endsection

@section("content")
    @include("office.partials.errors")

    <form method="POST" action="{{ route("office.dictionary.create.post") }}" class="validate">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                @include("office.dictionaries._form")
            </div>
            <div class="card-footer">
                @include("office.partials.buttons", ["back" => route("office.dictionaries")])
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection
