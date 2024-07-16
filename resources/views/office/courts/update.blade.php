@extends("office.layout-auth")

@section("title"){{ "Aktualizacja klienta" }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.courts") }}">{{ __("Baza sądów") }}</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.court.show", $court->id) }}">{{ $court->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Aktualizacja sądu") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:courts"])

    <form method="POST" action="{{ route("office.court.update.post", $id) }}" class="validate">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                @include("office.courts._form")
            </div>
            <div class="card-footer">
                @include("office.partials.buttons", ["back" => route("office.court.show", $court->id)])
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection
