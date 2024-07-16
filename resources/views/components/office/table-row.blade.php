<div class="lh-sm {{ $hasBorder() ? "border-bottom" : "" }} pb-2 mb-2">
    <div class="text-muted">
        <small>{{ __($label) }}:</small>
    </div>
    <div class="fs-6">
        @if(!empty($content) && !$content->isEmpty())
            {{ $content }}
        @else
            @if($route)
                <a href="{{ $route }}">
            @endif
            
            @if($yesNo())
                @if($value)
                    {{ __("TAK") }}
                @else
                    {{ __("NIE") }}
                @endif
            @else
                {!! $value !!}
            @endif
            
            {{ $slot }}
            
            @if($route)
                </a>
            @endif
        @endif
    </div>
</div>
