<div class="row">
    <div class="col-12 mb-3">
        <label for="name" class="form-label">{{ __("Nazwa") }}*</label>
        <input type="text" name="name" class="form-control" id="name" value="{{ $form["name"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
    <div class="col-5 mb-3">
        <label for="street" class="form-label">{{ __("Ulica") }}</label>
        <input type="text" name="street" class="form-control" id="street" value="{{ $form["street"] ?? "" }}">
        <small class="input-error-info"></small>
    </div>
    <div class="col-2 mb-3">
        <label for="zip" class="form-label">{{ __("Kod pocztowy") }}</label>
        <input type="text" name="zip" class="form-control" id="zip" value="{{ $form["zip"] ?? "" }}">
        <small class="input-error-info"></small>
    </div>
    <div class="col-5 mb-3">
        <label for="city" class="form-label">{{ __("Miasto") }}</label>
        <input type="text" name="city" class="form-control" id="city" value="{{ $form["city"] ?? "" }}">
        <small class="input-error-info"></small>
    </div>
    <div class="col-4 mb-3">
        <label for="phone" class="form-label">{{ __("Telefon") }}</label>
        <textarea name="phone" class="form-control" id="phone" rows="3">{{ $form["phone"] ?? "" }}</textarea>
        <small class="input-error-info"></small>
    </div>
    <div class="col-4 mb-3">
        <label for="fax" class="form-label">{{ __("FAX") }}</label>
        <textarea name="fax" class="form-control" id="fax" rows="3">{{ $form["fax"] ?? "" }}</textarea>
        <small class="input-error-info"></small>
    </div>
    <div class="col-4 mb-3">
        <label for="email" class="form-label">{{ __("E-mail") }}</label>
        <textarea name="email" class="form-control" id="email" rows="3">{{ $form["email"] ?? "" }}</textarea>
        <small class="input-error-info"></small>
    </div>
</div>