<div class="row mt-4">
    <div class="col-12 col-md-5 mb-3">
        <label for="customerName" class="form-label">{{ __("Nazwa klienta") }}*</label>
        <input type="text" name="customer_name" class="form-control" id="customerName" value="{{ $form["customer_name"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-md-4 mb-3">
        <label for="customerSignature" class="form-label">{{ __("Oznaczenie Klienta - numer sprawy klienta") }}*</label>
        <input type="text" name="customer_signature" class="form-control" id="customerSignature" value="{{ $form["customer_signature"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-md-3 mb-3">
        <label for="rsSignature" class="form-label">{{ __("Oznaczenie RS") }}*</label>
        <input type="text" name="rs_signature" class="form-control" id="rsSignature" value="{{ $form["rs_signature"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 mb-3">
        <label for="opponent" class="form-label">{{ __("Przeciwnik") }}*</label>
        <input type="text" name="opponent" class="form-control" id="opponent" value="{{ $form["opponent"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-md-4 mb-3">
        <label for="statusId" class="form-label">{{ __("Stan sprawy") }}*</label>
        <select name="status_id" class="form-select" id="statusId" data-validate="required">
            <option></option>
            @foreach($caseStatuses as $caseStatusId => $caseStatusLabel)
                <option value="{{ $caseStatusId }}" @if(($form["status_id"] ?? "") == $caseStatusId){{ "selected" }}@endif>{{ $caseStatusLabel }}</option>
            @endforeach
        </select>
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-md-4 mb-3">
        <label for="death" class="form-label">{{ __("Zgon") }}</label>
        <select name="death" class="form-select" id="death" onchange="Case.changeDeath(this)">
            <option value="0" @if(($form["death"] ?? "") == "0"){{ "selected" }}@endif>{{ __("Nie") }}</option>
            <option value="1" @if(($form["death"] ?? "") == "1"){{ "selected" }}@endif>{{ __("Tak") }}</option>
        </select>
    </div>
    <div class="col-12 col-md-4 mb-3">
        <label for="dateOfDeath" class="form-label">{{ __("Data zgonu") }}<span id="dateOfDeathRequiredMark" class="@if(($form["death"] ?? "") != "1"){{ "d-none" }}@endif">*</span></label>
        <input type="text" name="date_of_death" class="form-control datepicker" id="dateOfDeath" value="{{ $form["date_of_death"] ?? "" }}" data-validate="required" disabled>
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="insolvency" class="form-label">{{ __("Upadłość") }}</label>
        <select name="insolvency" class="form-select" id="insolvency">
            <option value="0" @if(($form["insolvency"] ?? "") == "0"){{ "selected" }}@endif>{{ __("Nie") }}</option>
            <option value="1" @if(($form["insolvency"] ?? "") == "1"){{ "selected" }}@endif>{{ __("Tak") }}</option>
        </select>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="completed" class="form-label">{{ __("Obsługa zakończona") }}</label>
        <select name="completed" class="form-select" id="completed">
            <option value="0" @if(($form["completed"] ?? "") == "0"){{ "selected" }}@endif>{{ __("Nie") }}</option>
            <option value="1" @if(($form["completed"] ?? "") == "1"){{ "selected" }}@endif>{{ __("Tak") }}</option>
        </select>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="baliff" class="form-label">{{ __("Komornik") }}</label>
        <input type="text" name="baliff" class="form-control" id="baliff" value="{{ $form["baliff"] ?? "" }}">
        <div class="form-text">{{ __("Pełna nazwa komornika") }}</div>
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="court" class="form-label">{{ __("Sąd") }}</label>
        <input type="text" name="court" class="form-control" id="court" value="{{ $form["court"] ?? "" }}">
        <div class="form-text">{{ __("Pełna nazwa sądu") }}</div>
        <small class="input-error-info"></small>
    </div>
</div>