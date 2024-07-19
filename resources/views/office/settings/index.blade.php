@extends("office.layout-auth")

@section("title"){{ __("Ustawienia") }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item active" aria-current="page">{{ __("Ustawienia") }}</li>
@endsection

@section("scripts")
    <script src="{{ a("/res/backend/js/modules/settings.js") }}"></script>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:settings"])
    
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link @if($activeTab == "currencies"){{ "active" }}@endif" data-bs-toggle="tab" href="#currencies" role="tab">{{ __("Waluty") }}</a>
                </li>
            </ul>
        </div>
        <div class="tab-content" id="tabs-base-data">
            @include("office.settings.partials.currencies")
        </div>
    </div>
@endsection