<div class="row mt-4">
    <div class="col-12 col-md-5 mb-3">
        <label for="customerName" class="form-label">{{ __("Nazwa klienta") }}*</label>
        <select class="form-control form-select-2" name="customer_id" id="customerName" data-validate="required" data-allow-clear="true" data-placeholder="{{ __("- wbierz klienta ") }}">
            <option></option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" @if($customer->id == ($form["customer_id"] ?? "")){{ "selected" }}@endif>{{ $customer->name }}</option>
            @endforeach
        </select>
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
    <div class="col-12 col-sm-3 mb-3">
        <label for="opponentPesel" class="form-label">{{ __("PESEL") }}</label>
        <input type="text" name="opponent_pesel" class="form-control" id="opponentPesel" value="{{ $form["opponent_pesel"] ?? "" }}" data-validate="pesel">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-sm-3 mb-3">
        <label for="opponentRegon" class="form-label">{{ __("REGON") }}</label>
        <input type="text" name="opponent_regon" class="form-control" id="opponentRegon" value="{{ $form["opponent_regon"] ?? "" }}">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-sm-3 mb-3">
        <label for="opponentNip" class="form-label">{{ __("NIP") }}</label>
        <input type="text" name="opponent_nip" class="form-control" id="opponentNip" value="{{ $form["opponent_nip"] ?? "" }}" data-validate="nip">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-sm-3 mb-3">
        <label for="opponentKrs" class="form-label">{{ __("KRS") }}</label>
        <input type="text" name="opponent_krs" class="form-control" id="opponentKrs" value="{{ $form["opponent_krs"] ?? "" }}">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-sm-4 mb-3">
        <label for="opponentStreet" class="form-label">{{ __("Ulica") }}</label>
        <input type="text" name="opponent_street" class="form-control" id="opponentStreet" value="{{ $form["opponent_street"] ?? "" }}">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-sm-4 mb-3">
        <label for="opponentZip" class="form-label">{{ __("Kod pocztowy") }}</label>
        <input type="text" name="opponent_zip" class="form-control" id="opponentZip" value="{{ $form["opponent_zip"] ?? "" }}">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-sm-4 mb-3">
        <label for="opponentCity" class="form-label">{{ __("Miasto") }}</label>
        <input type="text" name="opponent_city" class="form-control" id="opponentCity" value="{{ $form["opponent_city"] ?? "" }}">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-sm-6 mb-3">
        <label for="opponentPhone" class="form-label">{{ __("Telefon") }}</label>
        <input type="text" name="opponent_phone" class="form-control" id="opponentPhone" value="{{ $form["opponent_phone"] ?? "" }}">
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-sm-6 mb-3">
        <label for="opponentEmail" class="form-label">{{ __("Adres e-mail") }}</label>
        <input type="text" name="opponent_email" class="form-control" id="opponentEmail" value="{{ $form["opponent_email"] ?? "" }}">
        <small class="input-error-info"></small>
    </div>
</div>
    
<div class="row mt-5">
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
        <input type="text" name="date_of_death" class="form-control datepicker" id="dateOfDeath" value="{{ $form["date_of_death"] ?? "" }}" data-validate="required" @if(($form["death"] ?? "") != "1"){{ "disabled" }}@endif>
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
        <select class="form-select form-select-2" id="court" name="court_id" data-allow-clear="true" data-placeholder="">
            <option></option>
            @foreach($courts as $court)
                <option value="{{ $court->id }}" @if($court->id == ($form["court_id"] ?? "")){{ "selected" }}@endif>{{ $court->name }}</option>
            @endforeach
        </select>
        <div class="form-text">{{ __("Pełna nazwa sądu") }}</div>
        <small class="input-error-info"></small>
    </div>
</div>