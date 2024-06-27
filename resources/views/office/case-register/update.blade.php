@extends("office.layout-auth")

@section("title"){{ "Aktualizacja sprawy" }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.case_register") }}">{{ __("Rejestr spraw") }}</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.case_register.show", $case->id) }}">{{ __("Szczegóły sprawy") }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Aktualizacja sprawy") }}</li>
@endsection

@section("scripts")
    <script src="/res/office/js/modules/case.js"></script>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:cases"])
    
    <form method="POST" action="{{ route("office.case_register.update.post", $case->id) }}" class="validate">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                @include("office.case-register._form")
            </div>
            <div class="card-footer">
                @include("office.partials.buttons", ["back" => route("office.case_register.show", $case->id)])
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection