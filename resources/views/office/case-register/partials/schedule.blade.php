<div class="p-3 border-start border-bottom border-end">
    <div class="text-end mb-2">
        <button type="button" class="btn btn-primary btn-sm open-modal" data-modal="#scheduleModal">{{ __("Dodaj harmonogram") }}</button>
    </div>
        
    <form id="scheduleTableFilter" class="mt-4">
        <div class="row gx-3 align-items-end">
            <div class="col mb-2">
                <input type="text" class="form-control datepicker" name="date_from" placeholder="{{ __("Deklarowana data spłaty (od)") }}" autocomplete="off">
            </div>
            <div class="col mb-2">
                <input type="text" class="form-control datepicker" name="date_to" placeholder="{{ __("Deklarowana data spłaty (do)") }}" autocomplete="off">
            </div>
            <div class="col-auto mb-2">
                <div>
                    <button type="button" class="btn btn-secondary btn-icon d-inline" onclick="return App.clearFilterAjaxTable(this, '#scheduleTableContainer');"><i class="bi bi-x"></i></button>
                    <button type="button" class="btn btn-secondary" onclick="$('#scheduleTableContainer').ajaxTable('refresh'); return false;">{{ __("Szukaj") }}</button>
                </div>
            </div>
        </div>
    </form>
        
    <div id="scheduleTableContainer" class="ajax-table mt-3" data-page="1" data-toprecords="{{ config("office.lists.ajax.size") }}" data-url="{{ route("office.case_register.schedules", $case->id) }}" data-export-url="{{ route("office.case_register.schedules.export", $case->id) }}" data-filter-form="scheduleTableFilter" data-order="desc" data-sort="date">
        <div class="ajax-loading"><div class="ajax-loading-wheel"></div></div>
        <div class="table-responsive">
            <table class="table table-stripped table-hover" id="case-schedule">
                <thead>
                    <tr>
                        <th class="sortable sort-active sort-desc" data-field="date">{{ __("Deklarowany termin wpłaty") }}</th>
                        <th style="width: 220px;" class="text-end sortable" data-field="amount">{{ __("Wysokość deklarowanej raty") }}</th>
                        <th style="width: 80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @include("office.case-register.table.schedule-table")
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 bg-white">
            <div class="ajax-pagination float-end">
                {!! $schedules->render("office.partials.pagination") !!}
            </div>
        </div>
    </div>
        
    <div class="text-end">
        <button onclick="$('#scheduleTableContainer').ajaxTable('export', this); return false;" class="btn btn-sm btn-info">
            {{ __("Eksportuj") }} <i class="bi bi-download"></i>
        </button>
    </div>
</div>
    
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title" id="scheduleModalLabel">
                    <span class="header-new">{{ __("Dodaj harmonogram") }}</span>
                    <span class="header-edit">{{ __("Edytuj harmonogram") }}</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route("office.case_register.schedule.post", $case->id) }}" onsubmit="Case.saveScheduleForm(this); return false;">
                <div class="modal-body py-0">
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="formScheduleDate" class="form-label">{{ __("Deklarowany termin wpłaty") }}*</label>
                            <input type="text" name="date" id="formScheduleDate" class="form-control datepicker" data-validate="required" autocomplete="off">
                            <small class="input-error-info"></small>
                        </div>
                            
                        <div class="col-6">
                            <label for="formScheduleAmount" class="form-label">{{ __("Wysokość deklarowanej raty") }}*</label>
                            <input type="text" name="amount" id="formScheduleAmount" class="form-control" data-validate="required">
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