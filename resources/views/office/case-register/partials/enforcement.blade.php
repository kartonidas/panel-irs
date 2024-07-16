<div class="p-3 border-start border-bottom border-end">
    <div class="text-end mb-2">
        <button type="button" class="btn btn-primary btn-sm open-modal" data-modal="#enforcementModal">{{ __("Dodaj postępowanie egzekucyjne") }}</button>
    </div>
        
    <form id="enforcementTableFilter" class="mt-4">
        <div class="row gx-3 align-items-end">
            <div class="col mb-2">
                <input type="text" class="form-control" name="signature" placeholder="{{ __("Sygnatura") }}">
            </div>
            <div class="col mb-2">
                <select class="form-select" name="execution_status_id">
                    <option value="">{{ __("- status -") }}</option>
                    @foreach($dictionaries["caseExecutionStatuses"] as $statusId => $status)
                        <option value="{{ $statusId }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col mb-2">
                <input type="text" class="form-control datepicker" name="date_from" placeholder="{{ __("Data wniosku (od)") }}" autocomplete="off">
            </div>
            <div class="col mb-2">
                <input type="text" class="form-control datepicker" name="date_to" placeholder="{{ __("Data wniosku (do)") }}" autocomplete="off">
            </div>
            <div class="col-auto mb-2">
                <div>
                    <button type="button" class="btn btn-secondary btn-icon d-inline" onclick="return App.clearFilterAjaxTable(this, '#enforcementTableContainer');"><i class="bi bi-x"></i></button>
                    <button type="button" class="btn btn-secondary" onclick="$('#enforcementTableContainer').ajaxTable('refresh'); return false;">{{ __("Szukaj") }}</button>
                </div>
            </div>
        </div>
    </form>
        
    <div id="enforcementTableContainer" class="ajax-table mt-3" data-page="1" data-toprecords="{{ config("office.lists.ajax.size") }}" data-url="{{ route("office.case_register.enforcements", $case->id) }}" data-export-url="{{ route("office.case_register.enforcements.export", $case->id) }}" data-filter-form="enforcementTableFilter" data-order="desc" data-sort="date">
        <div class="ajax-loading"><div class="ajax-loading-wheel"></div></div>
        <div class="table-responsive">
            <table class="table table-stripped table-hover" id="case-enforcement">
                <thead>
                    <tr>
                        <th class="sortable" data-field="signature">{{ __("Sygnatura akt") }}</th>
                        <th class="sortable" data-field="baliff">{{ __("Nazwa komornika") }}</th>
                        <th class="sortable" data-field="execution_status_id">{{ __("Status egzekucji") }}</th>
                        <th class="sortable sort-active sort-desc" data-field="date">{{ __("Data wniosku egzekucyjnego") }}</th>
                        <th style="width: 80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @include("office.case-register.table.enforcement-table")
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 bg-white">
            <div class="ajax-pagination float-end">
                {!! $enforcements->render("office.partials.pagination") !!}
            </div>
        </div>
    </div>
        
    <div class="text-end">
        <button onclick="$('#enforcementTableContainer').ajaxTable('export', this); return false;" class="btn btn-sm btn-info">
            {{ __("Eksportuj") }} <i class="bi bi-download"></i>
        </button>
    </div>
</div>
    
<div class="modal fade" id="enforcementModal" tabindex="-1" aria-labelledby="enforcementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title" id="enforcementModalLabel">
                    <span class="header-new">{{ __("Dodaj postępowanie egzekucyjne") }}</span>
                    <span class="header-edit">{{ __("Edytuj postępowanie egzekucyjne") }}</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route("office.case_register.enforcement.post", $case->id) }}" onsubmit="Case.saveEnforcementForm(this); return false;">
                <div class="modal-body py-0">
                    <div class="row g-3">
                        <div class="col-4">
                            <label for="formEnforcementSignature" class="form-label">{{ __("Sygnatura akt") }}*</label>
                            <input type="text" class="form-control" name="signature" id="formEnforcementSignature" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-8">
                            <label for="formEnforcementBaliffName" class="form-label">{{ __("Nazwa komornika") }}*</label>
                            <input type="text" class="form-control" name="baliff" id="formEnforcementBaliffName" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-5">
                            <label for="formEnforcementBaliffStreet" class="form-label">{{ __("Ulica") }}*</label>
                            <input type="text" class="form-control" name="baliff_street" id="formEnforcementBaliffStreet" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-2">
                            <label for="formEnforcementBaliffZip" class="form-label">{{ __("Kod pocztowy") }}*</label>
                            <input type="text" class="form-control" name="baliff_zip" id="formEnforcementBaliffZip" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-5">
                            <label for="formEnforcementBaliffCity" class="form-label">{{ __("Miejscowość") }}*</label>
                            <input type="text" class="form-control" name="baliff_city" id="formEnforcementBaliffCity" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-6">
                            <label for="formEnforcementStatus" class="form-label">{{ __("Status egzekucji") }}*</label>
                            <select class="form-select" name="execution_status_id" id="formEnforcementStatus" data-validate="required">
                                <option></option>
                                @foreach($dictionaries["caseExecutionStatuses"] as $statusId => $statusName)
                                    <option value="{{ $statusId }}">{{ $statusName }}</option>
                                @endforeach
                            </select>
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-6">
                            <label for="formEnforcementDate" class="form-label">{{ __("Data wniosku egzekucyjnego") }}*</label>
                            <input type="text" class="form-control datepicker" name="date" id="formEnforcementDate" data-validate="required" autocomplete="off">
                            <small class="input-error-info"></small>
                        </div>
                            
                        <div class="col-6">
                            <label for="formEnforcementCostRepresentationExecutionProceeding" class="form-label">{{ __("Koszty zastępstwa w postępowaniu egzekucyjnym") }}</label>
                            <input type="number" min="0" step="0.01" class="form-control" name="cost_representation_execution_proceedings" id="formEnforcementCostRepresentationExecutionProceeding">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-6">
                            <label for="formEnforcementCost" class="form-label">{{ __("Zaliczki / koszty egzekucyjne") }}</label>
                            <input type="number" min="0" step="0.01" class="form-control" name="enforcement_costs" id="formEnforcementCost">
                            <small class="input-error-info"></small>
                        </div>
                            
                        <div class="col-4">
                            <label for="formEnforcementDateAgainstPayment" class="form-label">{{ __("Data odnotowania postanowienia o zakończeniu wobec zapłaty") }}</label>
                            <input type="text" class="form-control datepicker" name="date_against_payment" id="formEnforcementDateAgainstPayment" autocomplete="off">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-4">
                            <label for="formEnforcementIneffective" class="form-label">{{ __("Data odnotowania postanowienia o umorzeniu wobec stwierdzenia bezskuteczności egzekucji") }}</label>
                            <input type="text" class="form-control datepicker" name="date_ineffective" id="formEnforcementIneffective" autocomplete="off">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-4">
                            <label for="formEnforcementAnotherRedemption" class="form-label">{{ __("Data odnotowania innego umorzenia") }}</label>
                            <input type="text" class="form-control datepicker" name="date_another_redemption" id="formEnforcementAnotherRedemption" autocomplete="off">
                            <small class="input-error-info"></small>
                        </div>
                    </div>
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