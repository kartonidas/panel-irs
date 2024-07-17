@extends("office.layout-auth")

@section("title"){{ __("Dostęp do spraw")  }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.users") }}">{{ __("Pracownicy kancelarii") }}</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.user.update", $user->id) }}">{{ $user->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Dostęp do spraw") }}</li>
@endsection

@section("scripts")
    <script src="{{ a("/res/libs/table.js") }}"></script>
    <script src="{{ a("/res/office/js/modules/user.js") }}"></script>
@endsection

@section("content")
    <div class="text-end mb-2">
        <button type="button" class="btn btn-primary btn-sm open-modal" data-modal="#accessModal">{{ __("Dodaj dostęp") }}</button>
    </div>
        
    <div id="accessTableContainer" class="ajax-table mt-3" data-page="1" data-toprecords="{{ config("office.lists.ajax.size") }}" data-url="{{ route("office.user.selected_case_access.list", $user->id) }}" data-filter-form="accessTableFilter" data-order="asc" data-sort="created_at">
        <div class="ajax-loading"><div class="ajax-loading-wheel"></div></div>
        <div class="table-responsive">
            <table class="table table-stripped table-hover" id="user-access">
                <thead>
                    <tr>
                        <th style="width: 250px;">{{ __("Klient") }}</th>
                        <th style="width: 200px;">{{ __("Dostęp") }}</th>
                        <th>{{ __("Sprawy") }}</th>
                        <th style="width: 80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @include("office.users.table.case-access-table")
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 bg-white">
            <div class="ajax-pagination float-end">
                {!! $caseSelectedAccess->render("office.partials.pagination") !!}
            </div>
        </div>
    </div>
        
        
    <div class="modal fade" id="accessModal" tabindex="-1" aria-labelledby="accessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title" id="accessModalLabel">
                        <span class="header-new">{{ __("Dodaj dostep") }}</span>
                        <span class="header-edit">{{ __("Edytuj dostep") }}</span>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route("office.user.selected_case_access.access.post", $user->id) }}" onsubmit="User.saveAccessForm(this); return false;">
                    <div class="modal-body py-0">
                        <div class="row g-3">
                            <div class="col-6">
                                <label for="formAccessCustomer" class="form-label">{{ __("Klient") }}*</label>
                                <select class="form-select-2" name="customer_id" id="formAccessCustomer" data-placeholder="" data-allow-clear="true" data-validate="required" onchange="User.onAccessCustomerChange(this)">
                                    <option></option>
                                    @foreach($customers as $customerId => $customer)
                                        <option value="{{ $customerId }}" data-case-numbers="{{ implode(",", $customer["case_numbers"]) }}">{{ $customer["name"] }}</option>
                                    @endforeach
                                </select>
                                <small class="input-error-info"></small>
                            </div>
                            <div class="col-6">
                                <label for="formAccessType" class="form-label">{{ __("Rodzaj dostępu") }}*</label>
                                <select class="form-select" name="type" id="formAccessType" data-validate="required" onchange="User.onAccessTypeChange(this)">
                                    <option></option>
                                    @foreach($caseAccessTypes as $caseAccessType => $caseAccessName)
                                        <option value="{{ $caseAccessType }}">{{ $caseAccessName }}</option>
                                    @endforeach
                                </select>
                                <small class="input-error-info"></small>
                            </div>
                            <div class="col-12 d-none" id="formCaseNumbersContainer">
                                <label for="formCaseNumbers" class="form-label">{{ __("Numery spraw") }}*</label>
                                <div id="formCaseNumbersInputs">
                                    <div id="formCaseNumbersInputsCheckboxes"></div>
                                    <div class="form-text text-danger" id="formCaseNumbersInputsSelectCustomerDanger">
                                        {{ __("Wybierz klienta, aby wybrac sprawy.") }}
                                    </div>
                                    <div class="form-text text-danger d-none" id="formCaseNumbersInputsNoCaseNumbersDanger">
                                        {{ __("Klient nie ma zdefiniowanych numerów spraw.") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="case_numbers">
                        <input type="hidden" name="id">
    
                        <div class="alert alert-danger alert-modal-error mt-2 d-none"></div>
                    </div>
    
                    <div class="modal-footer border-0 d-flex gap-2">
                        <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">{{ __("Zamknij") }}</button>
                        <button type="submit" class="btn btn-primary btn-submit flex-fill">{{ __("Zapisz") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection