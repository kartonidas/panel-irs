<?php
    $message = \App\Libraries\Helper::getMessage($module);
    $warning = \App\Libraries\Helper::getMessage($module, "Warning");
?>

@if(isset($message))
    @foreach($message as $msg)
        <div class="alert alert-success" role="alert">
            {{ $msg }}
        </div>
    @endforeach
@endif

@if(isset($warning))
    @foreach($warning as $msg)
        <div class="alert alert-warning" role="alert">
            {{ $msg }}
        </div>
    @endforeach
@endif