<div class="row">
    <div class="col-12 col-md-4 mb-3">
        <label for="nip" class="form-label">{{ __("NIP") }}*</label>
        <input type="text" name="nip" class="form-control" id="nip" value="{{ $form["nip"] ?? "" }}" data-validate="required|nip">
        <small class="input-error-info"></small>
    </div>
        
    <div class="col-12 col-md-4 mb-3">
        <label for="regon" class="form-label">{{ __("REGON") }}*</label>
        <input type="text" name="regon" class="form-control" id="regon" value="{{ $form["regon"] ?? "" }}" data-validate="required|regon">
        <small class="input-error-info"></small>
    </div>
        
    <div class="col-12 col-md-4 mb-3">
        <label for="krs" class="form-label">{{ __("KR") }}*</label>
        <input type="text" name="kr" class="form-control" id="kr" value="{{ $form["kr"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
    
    <div class="col-12 mb-3">    
        <div class="form-check">
            <input class="form-check-input" name="active" type="checkbox" value="1" id="active" @if(!empty($form["active"])){{ "checked" }}@endif>
            <label class="form-check-label" for="active">
                {{ __("Konto aktywne") }}
            </label>
        </div>
    </div>
        
    <div class="col-12 mb-3">
        <label for="name" class="form-label">{{ __("Nazwa") }}*</label>
        <input type="text" name="name" class="form-control" id="name" value="{{ $form["name"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>

    <div class="col-6 mb-3">
        <label for="street" class="form-label">{{ __("Adres") }}*</label>
        <input type="text" name="street" class="form-control" id="street" value="{{ $form["street"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
        
    <div class="col-12 col-md-3 mb-3">
        <label for="houseNo" class="form-label">{{ __("Numer domu") }}*</label>
        <input type="text" name="house_no" class="form-control" id="houseNo" value="{{ $form["house_no"] ?? "" }}"data-validate="required">
        <small class="input-error-info"></small>
    </div>
        
    <div class="col-12 col-md-3 mb-3">
        <label for="apartmentNo" class="form-label">{{ __("Numer lokalu") }}</label>
        <input type="text" name="apartment_no" class="form-control" id="apartmentNo" value="{{ $form["apartment_no"] ?? "" }}" data-gus="apartment_no">
        <small class="input-error-info"></small>
    </div>
        
    <div class="col-12 col-md-8 mb-3">
        <label for="city" class="form-label">{{ __("Miejscowość") }}*</label>
        <input type="text" name="city" class="form-control" id="city" value="{{ $form["city"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
        
    <div class="col-12 col-md-4 mb-3">
        <label for="zip" class="form-label">{{ __("Kod pocztowy") }}*</label>
        <input type="text" name="zip" class="form-control" id="registerFirmZip" value="{{ $form["zip"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
        
    <div class="col-12 mb-3">
        <label for="caseNumbers" class="form-label">{{ __("Oznaczenie numerów spraw") }}</label>
        <textarea name="case_numbers" class="form-control" rows="5" id="caseNumbers">{{ $form["case_numbers"] ?? "" }}</textarea>
        <div class="form-text">
            {{ __("Poszczególne numery możesz rozdzielić znakiem nowej linii, średnikiem, przecinkiem lub spacją.") }}
        </div>
        <small class="input-error-info"></small>
    </div>
</div>