{{ __("Pokaż: ") }}
<select class="form-select form-select-sm w-auto ms-2" onchange="App.changePageSize(this);">
    @foreach(config("office.lists.sizes") as $size)
        <option value="{{ $size }}" @if($size == $currentSize){{ "selected" }}@endif data-url="{{ route($route, $size, false) }}">{{ $size }}</option>
    @endforeach
</select>