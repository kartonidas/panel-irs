<div class="col">
    <label for="extra-{{ $type }}-document_signature" class="form-label">{{ __("Oznaczene dokumentu") }}*</label>
    <input type="text" name="extra[{{ $type }}][document_signature]" class="form-control" id="extra-{{ $type }}-document_signature" value="{{ $form["extra"][$type]["document_signature"] ?? "" }}" data-validate="required">
    <small class="input-error-info"></small>
</div>
