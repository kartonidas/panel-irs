<div class="p-3 border-start border-bottom border-end">
    <div class="text-end mb-2">
        <button type="button" class="btn btn-primary btn-sm open-modal" data-modal="#historyModal">{{ __("Dodaj czynność") }}</button>
    </div>
        
    <form id="historyTableFilter" class="mt-4">
        <div class="row gx-3 align-items-end">
            <div class="col mb-2">
                <select class="form-select" name="history_action_id">
                    <option value="">{{ __("- czynność -") }}</option>
                    @foreach($dictionaries["historyActions"] as $actionId => $action)
                        <option value="{{ $actionId }}">{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col mb-2">
                <input type="text" class="form-control datepicker" name="date_from" placeholder="{{ __("Data wykonania (od)") }}" autocomplete="off">
            </div>
            <div class="col mb-2">
                <input type="text" class="form-control datepicker" name="date_to" placeholder="{{ __("Data wykonania (do)") }}" autocomplete="off">
            </div>
            <div class="col-auto mb-2">
                <div>
                    <button type="button" class="btn btn-secondary btn-icon d-inline" onclick="return App.clearFilterAjaxTable(this, '#historyTableContainer');"><i class="bi bi-x"></i></button>
                    <button type="button" class="btn btn-secondary" onclick="$('#historyTableContainer').ajaxTable('refresh'); return false;">{{ __("Szukaj") }}</button>
                </div>
            </div>
        </div>
    </form>
        
    <div id="historyTableContainer" class="ajax-table mt-3" data-page="1" data-toprecords="{{ config("office.lists.ajax.size") }}" data-url="{{ route("office.case_register.histories", $case->id) }}" data-export-url="{{ route("office.case_register.histories.export", $case->id) }}" data-filter-form="historyTableFilter" data-order="desc" data-sort="date">
        <div class="ajax-loading"><div class="ajax-loading-wheel"></div></div>
        <div class="table-responsive">
            <table class="table table-stripped table-hover" id="case-history">
                <thead>
                    <tr>
                        <th style="width: 250px;" class="sortable" data-field="history_action_id">{{ __("Czynność") }}</th>
                        <th>{{ __("Opis") }}</th>
                        <th style="width: 150px;" class="text-center sortable sort-active sort-desc" data-field="date">{{ __("Data wykonania") }}</th>
                        <th style="width: 80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @include("office.case-register.table.history-table")
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 bg-white">
            <div class="ajax-pagination float-end">
                {!! $histories->render("office.partials.pagination") !!}
            </div>
        </div>
    </div>
        
    <div class="text-end">
        <button onclick="$('#historyTableContainer').ajaxTable('export', this); return false;" class="btn btn-sm btn-info">
            {{ __("Eksportuj") }} <i class="bi bi-download"></i>
        </button>
    </div>
</div>
    
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title" id="historyModalLabel">
                    <span class="header-new">{{ __("Dodaj czynność") }}</span>
                    <span class="header-edit">{{ __("Edytuj czynność") }}</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route("office.case_register.history.post", $case->id) }}" onsubmit="Case.saveHistoryForm(this); return false;">
                <div class="modal-body py-0">
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="formHistoryAction" class="form-label">{{ __("Czynność") }}*</label>
                            <select class="form-select" name="history_action_id" id="formHistoryAction" data-validate="required">
                                @foreach($dictionaries["historyActions"] as $actionId => $action)
                                    <option value="{{ $actionId }}">{{ $action }}</option>
                                @endforeach
                            </select>
                            <small class="input-error-info"></small>
                        </div>
                        <div class="col-6">
                            <label for="formHistoryDate" class="form-label">{{ __("Data") }}*</label>
                            <input type="text" name="date" id="formHistoryDate" class="form-control datepicker" data-validate="required" autocomplete="off">
                            <small class="input-error-info"></small>
                        </div>
                            
                        <div class="col-12">
                            <label for="formHistoryDescription" class="form-label">{{ __("Opis") }}</label>
                            <textarea class="form-control" id="formHistoryDescription" name="description" rows="3"></textarea>
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