{!! '<'.'?xml version="1.0"?>' !!}
<export>
    <header>{{ __("Sygnatura akt wpłaty") }}</header>
    <header>{{ __("Nazwa komornika") }}</header>
    <header>{{ __("Status egzekucji") }}</header>
    <header>{{ __("Data wniosku egzekucyjnego") }}</header>

    @if(!$enforcements->isEmpty())
       @foreach($enforcements as $enforcement)
           <data>
                <signature>{{ $enforcement->signature }}</signature>
                <baliff>{{ $enforcement->baliff }}</baliff>
                <status>{{ $enforcement->getExecutionStatusName() }}</status>
                <date>{{ $enforcement->date }}</date>
           </data>
       @endforeach
   @endif
</export>
