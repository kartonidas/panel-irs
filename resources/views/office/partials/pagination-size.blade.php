@php
    $currentSize = \App\Libraries\Helper::getPageSize($m);
@endphp

{{ __("Pokaż: ") }}
<select class="form-select form-select-sm w-auto ms-2" onchange="App.changePageSize(this);">
    @foreach(config("backend.lists.sizes") as $size)
        <option value="{{ $size }}" @if($size == $currentSize){{ "selected" }}@endif data-url="{{ route("backend.page-size", [$m, $size], false) }}">{{ $size }}</option>
    @endforeach
</select>