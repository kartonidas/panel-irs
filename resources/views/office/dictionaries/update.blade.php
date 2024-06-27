@extends("office.layout-auth")

@section("title"){{ "Aktualizacja wartości słownikowej" }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.dictionaries") }}">{{ __("Słowniki") }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Aktualizacja wartości słownikowej") }}</li>
@endsection

@section("scripts")
    <script src="{{ a("/res/office/js/modules/dictionary.js") }}"></script>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:dictionaries"])

    <form method="POST" action="{{ route("office.dictionary.update.post", $id) }}" class="validate">
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
