<?php
    $message = \App\Libraries\Helper::getMessage($module);
?>

@if(isset($message))
    @foreach($message as $msg)
        <div class="alert alert-success" role="alert">
            {{ $msg }}
        </div>
    @endforeach
@endif