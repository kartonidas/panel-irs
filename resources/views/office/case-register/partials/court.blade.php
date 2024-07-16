<div class="p-3 border-start border-bottom border-end">
    <div class="text-end mb-2">
        <button type="button" class="btn btn-primary btn-sm open-modal" data-modal="#courtModal">{{ __("Dodaj postępowanie sądowe") }}</button>
    </div>
    
    <form id="courtTableFilter" class="mt-4">
        <div class="row gx-3 align-items-end">
            <div class="col mb-2">
                <input type="text" class="form-control" name="signature" placeholder="{{ __("Sygnatura") }}">
            </div>
            <div class="col mb-2">
                <select class="form-select" name="status_id">
                    <option value="">{{ __("- status -") }}</option>
                    @foreach($dictionaries["caseStatuses"] as $statusId => $status)
                        <option value="{{ $statusId }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col mb-2">
                <select class="form-select" name="mode_id">
                    <option value="">{{ __("- tryb postępownia -") }}</option>
                    @foreach($dictionaries["caseModes"] as $modeId => $mode)
                        <option value="{{ $modeId }}">{{ $mode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col mb-2">
                <input type="text" class="form-control datepicker" name="date_from" placeholder="{{ __("Data pozwu (od)") }}" autocomplete="off">
            </div>
            <div class="col mb-2">
                <input type="text" class="form-control datepicker" name="date_to" placeholder="{{ __("Data pozwu (do)") }}" autocomplete="off">
            </div>
            <div class="col-auto mb-2">
                <div>
                    <button type="button" class="btn btn-secondary btn-icon d-inline" onclick="return App.clearFilterAjaxTable(this, '#courtTableContainer');"><i class="bi bi-x"></i></button>
                    <button type="button" class="btn btn-secondary" onclick="$('#courtTableContainer').ajaxTable('refresh'); return false;">{{ __("Szukaj") }}</button>
                </div>
            </div>
        </div>
    </form>
    
    <div id="courtTableContainer" class="ajax-table mt-3" data-page="1" data-toprecords="{{ config("office.lists.ajax.size") }}" data-url="{{ route("office.case_register.courts", $case->id) }}" data-export-url="{{ route("office.case_register.courts.export", $case->id) }}" data-filter-form="courtTableFilter" data-order="desc" data-sort="date">
        <div class="ajax-loading"><div class="ajax-loading-wheel"></div></div>
        <div class="table-responsive">
            <table class="table table-stripped table-hover" id="case-court">
                <thead>
                    <tr>
                        <th class="sortable" data-field="signature">{{ __("Sygnatura akt") }}</th>
                        <th class="sortable" data-field="court_id">{{ __("Nazwa sądu / wydziału") }}</th>
                        <th class="sortable" data-field="status_id">{{ __("Status") }}</th>
                        <th class="sortable" data-field="mode_id">{{ __("Tryb postępowania") }}</th>
                        <th class="sortable sort-active sort-desc" data-field="date">{{ __("Data pozwu") }}</th>
                        <th style="width: 80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @include("office.case-register.table.court-table")
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 bg-white">
            <div class="ajax-pagination float-end">
                {!! $courts->render("office.partials.pagination") !!}
            </div>
        </div>
    </div>
        
    <div class="text-end">
        <button onclick="$('#courtTableContainer').ajaxTable('export', this); return false;" class="btn btn-sm btn-info">
            {{ __("Eksportuj") }} <i class="bi bi-download"></i>
        </button>
    </div>
</div>
    
<div class="modal fade" id="courtModal" tabindex="-1" aria-labelledby="courtModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title" id="courtModalLabel">
                    <span class="header-new">{{ __("Dodaj postępowanie sądowe") }}</span>
                    <span class="header-edit">{{ __("Edytuj postępowanie sądowe") }}</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route("office.case_register.court.post", $case->id) }}" onsubmit="Case.saveCourtForm(this); return false;">
                <div class="modal-body py-0">
                    <div class="row g-3">
                        <div class="col-4">
                            <label for="formCourtSignature" class="form-label">{{ __("Sygnatura akt") }}*</label>
                            <input type="text" class="form-control" name="signature" id="formCourtSignature" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-4">
                            <label for="formCourtCourtName" class="form-label">{{ __("Nazwa sądu") }}*</label>
                            <select class="form-control form-select-2" name="court_id" data-placeholder="{{ __("Wybierz sąd") }}" data-allow-clear="true" id="formCourtCourtName" data-vaidate="required" onchange="Case.changeCourt(this, event)">
                                <option></option>
                                @foreach($dictionaries["courts"] as $court)
                                    <option value="{{ $court->id }}" data-info="{{ $court }}">{{ $court->name }}</option>
                                @endforeach
                            </select>
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-4">
                            <label for="formCourtCourtDepartment" class="form-label">{{ __("Wydział") }}*</label>
                            <input type="text" class="form-control" name="department" id="formCourtCourtDepartment" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-5">
                            <label for="formCourtCourtStreet" class="form-label">{{ __("Ulica") }}*</label>
                            <input type="text" class="form-control" name="court_street" id="formCourtCourtStreet" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-2">
                            <label for="formCourtCourtZip" class="form-label">{{ __("Kod pocztowy") }}*</label>
                            <input type="text" class="form-control" name="court_zip" id="formCourtCourtZip" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-5">
                            <label for="formCourtCourtCity" class="form-label">{{ __("Miejscowość") }}*</label>
                            <input type="text" class="form-control" name="court_city" id="formCourtCourtCity" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-6">
                            <label for="formCourtStatus" class="form-label">{{ __("Status") }}*</label>
                            <select class="form-select" name="status_id" id="formCourtStatus" data-validate="required">
                                <option></option>
                                @foreach($dictionaries["caseStatuses"] as $statusId => $statusName)
                                    <option value="{{ $statusId }}">{{ $statusName }}</option>
                                @endforeach
                            </select>
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-6">
                            <label for="formCourtMode" class="form-label">{{ __("Tryb postępowania") }}*</label>
                            <select class="form-select" name="mode_id" id="formCourtMode" data-validate="required">
                                <option></option>
                                @foreach($dictionaries["caseModes"] as $modeId => $modeName)
                                    <option value="{{ $modeId }}">{{ $modeName }}</option>
                                @endforeach
                            </select>
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-4">
                            <label for="formCourtDate" class="form-label">{{ __("Data pozwu") }}*</label>
                            <input type="text" class="form-control datepicker" name="date" id="formCourtDate" data-validate="required" autocomplete="off">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-4">
                            <label for="formCourtDateEnforcement" class="form-label">{{ __("Data uzyskania tytułu egzekucyjnego") }}</label>
                            <input type="text" class="form-control datepicker" name="date_enforcement" id="formCourtDateEnforcement" autocomplete="off">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-4">
                            <label for="formCourtDateExecution" class="form-label">{{ __("Data uzyskania tytułu wykonawczego") }}</label>
                            <input type="text" class="form-control datepicker" name="date_execution" id="formCourtDateExecution" autocomplete="off">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-6">
                            <label for="formCourtCostRepresentationCourtProceeding" class="form-label">{{ __("Koszty zastępstwa w postępowaniu sądowym") }}</label>
                            <input type="number" min="0" step="0.01" class="form-control" name="cost_representation_court_proceedings" id="formCourtCostRepresentationCourtProceeding">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-6">
                            <label for="formCourtCostRepresentationClauseProceeding" class="form-label">{{ __("Koszty zastępstwa w postępowaniu klauzulowym") }}</label>
                            <input type="number" min="0" step="0.01" class="form-control" name="cost_representation_clause_proceedings" id="formCourtCostRepresentationClauseProceeding">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-4">
                            <label for="formCourtEpuWarrantyCode" class="form-label">{{ __("Kod nakazu EPU") }}</label>
                            <input type="text" class="form-control" name="code_epu_warranty" id="formCourtEpuWarrantyCode">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-4">
                            <label for="formCourtEpuClauseCode" class="form-label">{{ __("Kod klauzuli EPU") }}</label>
                            <input type="text" class="form-control" name="code_epu_clause" id="formCourtEpuClauseCode">
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-4">
                            <label for="formCourtEpuFilesCode" class="form-label">{{ __("Kod dostępu do akt EPU") }}</label>
                            <input type="text" class="form-control" name="code_epu_files" id="formCourtEpuFilesCode">
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