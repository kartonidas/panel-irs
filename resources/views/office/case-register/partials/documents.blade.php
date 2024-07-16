<div class="p-3 border-start border-bottom border-end">
    <div class="text-end mb-2">
        <button type="button" class="btn btn-primary btn-sm open-modal @if(!$hasSftp){{ "disabled" }}@endif" data-modal="#documentModal" data-callback="Case.newDocument">{{ __("Dodaj dokument") }}</button>
        @if(!$hasSftp)
            <small class="d-block text-danger">{{ __("Brak skonfigurowanego połączenie SFTP") }}</small>
        @endif
    </div>
        
    <div id="documentTableContainer" class="ajax-table mt-3" data-page="1" data-toprecords="{{ config("office.lists.ajax.size") }}" data-url="{{ route("office.case_register.documents", $case->id) }}" data-order="desc" data-sort="date">
        <div class="ajax-loading"><div class="ajax-loading-wheel"></div></div>
        <div class="table-responsive">
            <table class="table table-stripped table-hover" id="case-court">
                <thead>
                    <tr>
                        <th class="sortable sort-active sort-desc" data-field="date" style="width: 150px;">{{ __("Data dodania") }}</th>
                        <th class="sortable" data-field="name">{{ __("Nazwa") }}</th>
                        <th style="width: 350px;">{{ __("Plik") }}</th>
                        <th style="width: 80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @include("office.case-register.table.document-table")
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 bg-white">
            <div class="ajax-pagination float-end">
                {!! $documents->render("office.partials.pagination") !!}
            </div>
        </div>
    </div>
</div>
    
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title" id="documentModalLabel">
                    <span class="header-new">{{ __("Dodaj dokument") }}</span>
                    <span class="header-edit">{{ __("Edytuj dokument") }}</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route("office.case_register.document.post", $case->id) }}" onsubmit="Case.saveDocumentForm(this); return false;" data-file="true">
                <div class="modal-body py-0">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="formDocumentName" class="form-label">{{ __("Nazwa dokumentu") }}*</label>
                            <input type="text" name="name" id="formDocumentName" class="form-control" data-validate="required">
                            <small class="input-error-info"></small>
                        </div>
                            
                        <div class="col-12" id="documentUploadContainer">
                            <label for="formDocumentFile" class="form-label">{{ __("Plik") }}*</label>
                            <input type="file" name="document" id="formDocumentFile" class="form-control" data-validate="required">
                            <div class="form-text">
                                {{ __("Dozwolone pliki") }}: {{ implode(", ", config("files.case_allowed_extensions")) }}.
                                {{ __("Maksymalny rozmiar pliku") }}: {{ config("files.case_allowed_extensions_max_size") }}MB
                            </div>
                            <small class="input-error-info"></small>
                        </div>
                            
                        <div class="col-12 d-none" id="documentReplaceFileContainer">
                            <label for="formDocumentFile" class="form-label">{{ __("Plik") }}</label>
                            <div class="d-flex">
                                <input type="text" class="form-control w-50 me-3" name="origfile" disabled>
                                <button type="button" class="btn btn-sm btn-danger" onclick="Case.changeDocumentFile(this)">{{ __("Zmień plik") }}</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id">
                    <input type="hidden" name="replace_file" value="0">
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