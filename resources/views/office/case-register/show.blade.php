@extends("office.layout-auth")

@section("title"){{ "Szczegóły sprawy" }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.case_register") }}">{{ __("Rejestr spraw") }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Szczegóły sprawy") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:cases"])
    
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <button class="nav-link active" id="nav-base-data-tab" data-bs-toggle="tab" data-bs-target="#base-data-tab" type="button">{{ __("Sprawa") }}</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="nav-financial-summary-tab" data-bs-toggle="tab" data-bs-target="#financial-summary-tab" type="button">{{ __("Finanse") }}</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="nav-claims-list-tab" data-bs-toggle="tab" data-bs-target="#claims-list-tab" type="button">{{ __("Roszczenia") }}</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="nav-history-tab" data-bs-toggle="tab" data-bs-target="#history-tab" type="button">{{ __("Historia czynności") }}</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="nav-schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule-tab" type="button">{{ __("Harmonogram spłat") }}</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="nav-court-tab" data-bs-toggle="tab" data-bs-target="#court-tab" type="button">{{ __("Post. sądowe") }}</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="nav-enforcement-tab" data-bs-toggle="tab" data-bs-target="#enforcement-tab" type="button">{{ __("Post. egzekucyjne") }}</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="nav-documents-tab" data-bs-toggle="tab" data-bs-target="#documents-tab" type="button">{{ __("Dokumenty") }}</button>
        </li>
    </ul>
    
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="base-data-tab" role="tabpanel" aria-labelledby="base-data-tab">
            @include("office.case-register.partials.base-data")
        </div>
        <div class="tab-pane fade" id="financial-summary-tab" role="tabpanel" aria-labelledby="financial-summary-tab">
            @include("office.case-register.partials.financial-summary")
        </div>
        <div class="tab-pane fade" id="claims-list-tab" role="tabpanel" aria-labelledby="claims-list-tab">
            @include("office.case-register.partials.claims-list")
        </div>
        <div class="tab-pane fade" id="history-tab" role="tabpanel" aria-labelledby="history-tab">
            @include("office.case-register.partials.history")
        </div>
        <div class="tab-pane fade" id="schedule-tab" role="tabpanel" aria-labelledby="schedule-tab">
            @include("office.case-register.partials.schedule")
        </div>
        <div class="tab-pane fade" id="court-tab" role="tabpanel" aria-labelledby="court-tab">
            @include("office.case-register.partials.court")
        </div>
        <div class="tab-pane fade" id="enforcement-tab" role="tabpanel" aria-labelledby="enforcement-tab">
            @include("office.case-register.partials.enforcement")
        </div>
        <div class="tab-pane fade" id="documents-tab" role="tabpanel" aria-labelledby="documents-tab">
            @include("office.case-register.partials.documents")
        </div>
    </div>
@endsection