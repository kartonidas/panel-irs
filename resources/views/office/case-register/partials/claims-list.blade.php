<div class="p-3 border-start border-bottom border-end">
    <div class="text-end mb-2">
        <button type="button" class="btn btn-primary btn-sm open-modal" data-modal="#claimModal">{{ __("Dodaj roszczenie") }}</button>
    </div>
    
    <form id="claimTableFilter" class="mt-4">
        <div class="row gx-3 align-items-end">
            <div class="col mb-2">
                <input type="text" name="mark" class="form-control" placeholder="{{ __("Oznaczenie roszczenia") }}">
            </div>
            <div class="col-auto mb-2">
                <div>
                    <button type="button" class="btn btn-secondary btn-icon d-inline" onclick="return App.clearFilterAjaxTable(this, '#claimsTableContainer');"><i class="bi bi-x"></i></button>
                    <button type="button" class="btn btn-secondary" onclick="$('#claimsTableContainer').ajaxTable('refresh'); return false;">{{ __("Szukaj") }}</button>
                </div>
            </div>
        </div>
    </form>
    
    <div id="claimsTableContainer" class="ajax-table mt-3" data-page="1" data-toprecords="{{ config("office.lists.ajax.size") }}" data-url="{{ route("office.case_register.claims", $case->id) }}" data-export-url="{{ route("office.case_register.claims.export", $case->id) }}" data-filter-form="claimTableFilter" data-order="desc" data-sort="due_date">
        <div class="ajax-loading"><div class="ajax-loading-wheel"></div></div>
        <div class="table-responsive">
            <table class="table table-stripped table-hover" id="case-claims">
                <thead>
                    <tr>
                        <th style="width: 150px;" class="sortable" data-field="amount">{{ __("Roszczenie") }}</th>
                        <th style="width: 150px;" class="text-center sortable" data-field="date">{{ __("Data wystawienia") }}</th>
                        <th style="width: 150px;" class="text-center sortable sort-active sort-desc" data-field="due_date">{{ __("Termin wymagalności") }}</th>
                        <th style="width: 150px;" class="sortable" data-field="mark">{{ __("Oznaczenie roszczenia") }}</th>
                        <th>{{ __("Opis") }}</th>
                        <th style="width: 80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @include("office.case-register.table.claims-table")
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 bg-white">
            <div class="ajax-pagination float-end">
                {!! $claims->render("office.partials.pagination") !!}
            </div>
        </div>
    </div>
        
    <div class="text-end">
        <button onclick="$('#claimsTableContainer').ajaxTable('export', this); return false;" class="btn btn-sm btn-info">
            {{ __("Eksportuj") }} <i class="bi bi-download"></i>
        </button>
    </div>
</div>
    
<div class="modal fade" id="claimModal" tabindex="-1" aria-labelledby="claimModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title" id="claimModalLabel">
                    <span class="header-new">{{ __("Dodaj roszczenie") }}</span>
                    <span class="header-edit">{{ __("Edytuj roszczenie") }}</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route("office.case_register.claim.post", $case->id) }}" onsubmit="Case.saveClaimForm(this); return false;">
                <div class="modal-body py-0">
                    <div class="row g-3">
                        <div class="col-4">
                            <label for="formClaimAmount" class="form-label">{{ __("Kwota") }}*</label>
                            <input type="number" name="amount" id="formClaimAmount" step="0.01" min="0.01" class="form-control" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                            
                        <div class="col-8">
                            <label for="formClaimMark" class="form-label">{{ __("Oznaczenie roszczenia") }}*</label>
                            <input type="text" name="mark" id="formClaimMark" class="form-control" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                            
                        <div class="col-6">
                            <label for="formClaimDate" class="form-label">{{ __("Data wystawienia") }}*</label>
                            <input type="text" name="date" id="formClaimDate" class="form-control datepicker" data-validate="required" autocomplete="off">
                            <small class="input-error-info"></small>
                        </div>
                            
                        <div class="col-6">
                            <label for="formClaimDueDate" class="form-label">{{ __("Termin wymagalności") }}*</label>
                            <input type="text" name="due_date" id="formClaimDueDate" class="form-control datepicker" data-validate="required" autocomplete="off">
                            <small class="input-error-info"></small>
                        </div>
                            
                        <div class="col-12">
                            <label for="formClaimDescription" class="form-label">{{ __("Opis") }}</label>
                            <textarea class="form-control" id="formClaimDescription" name="description" rows="3"></textarea>
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