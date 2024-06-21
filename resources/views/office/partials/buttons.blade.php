<div class="d-flex justify-content-between">
    <div>
        @if(!empty($back))
            <a href="{{ $back }}" class="btn btn-secondary">
                <i class="bi bi-chevron-left"></i>
                {{ __("Anuluj") }}
            </a>
        @endif
    </div>
    <div>
        @if(empty($hideSave))
            <button type="submit" class="btn btn-success btn-submit" data-text="Zapisz">
                {{ __("Zapisz") }}
                @if(!empty($showSaveChevronIcon))
                    <i class="bi bi-chevron-right"></i>
                @endif
            </button>
        @endif
        @if(empty($hideSaveAndEdit))
            <button type="submit" class="btn btn-primary btn-submit btn-submit-apply" data-text="Zapisz i pozostań w edycji" onclick="return App.formApply(this)">{{ __("Zapisz i pozostań w edycji") }}</button>
        @endif
    </div>
</div>