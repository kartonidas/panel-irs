<div class="row">
    <div class="col-12 col-md-6 mb-3">
        <label for="type" class="form-label">{{ __("Rodzaj") }}*</label>
        <select name="type" class="form-select" id="type" data-validate="required" @if(!empty($id)){{ "disabled" }}@endif onchange="Dictionary.changeDictionaryType(this)">
            <option></option>
            @foreach($dictionaryTypes as $k => $type)
                <option value="{{ $k }}" @if($k == ($form["type"] ?? "")){{ "selected" }}@endif>{{ $type["name"] }}</option>
            @endforeach
        </select>
        <small class="input-error-info"></small>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="value" class="form-label">{{ __("Wartość") }}*</label>
        <input type="text" name="value" class="form-control" id="value" value="{{ $form["value"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
</div>
    
@foreach($dictionaryTypes as $k => $type)
    @if(view()->exists("office.dictionaries.partials." . $k))
        <div class="row-by-type row @if($k != ($form["type"] ?? "")){{ "d-none" }}@endif" id="by-type-{{ $k }}">
            @include("office.dictionaries.partials." . $k, ["type" => $k])
        </div>
    @endif
@endforeach