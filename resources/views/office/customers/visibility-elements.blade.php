@extends("office.layout-auth")

@section("title"){{ $customer->name }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.customers") }}">{{ __("Klienci") }}</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.customer.show", $customer->id) }}">{{ $customer->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Widoczność elementów") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:customers"])
    <form method="POST" action="{{ route("office.customer.visibility-elements.post", $customer->id) }}" class="validate">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                <div class="row">
                    @foreach($fieldsVisibility as $i => $section)
                        <div class="col-12 col-md-4 mb-4">
                            <div class="fs-5 border-bottom">{{ $section["label"] }}</div>
                            <div class="pt-2">
                                @foreach($section["fields"] as $key => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" name="visibility[{{ $i }}][]" type="checkbox" value="{{ $key }}" id="field-{{ $i }}-{{ $key }}" @if(in_array($key, $customerVisibilityFields[$i] ?? [])){{ "checked" }}@endif>
                                        <label class="form-check-label" for="field-{{ $i }}-{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                @include("office.partials.buttons", ["back" => route("office.customer.show", $customer->id), "hideSaveAndEdit" => true])
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection