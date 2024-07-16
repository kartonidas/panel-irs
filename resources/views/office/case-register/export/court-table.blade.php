{!! '<'.'?xml version="1.0"?>' !!}
<export>
    <header>{{ __("Sygnatura akt") }}</header>
    <header>{{ __("Nazwa sądu") }}</header>
    <header>{{ __("Wydział") }}</header>
    <header>{{ __("Adres") }}</header>
    <header>{{ __("Status") }}</header>
    <header>{{ __("Tryb postępowania") }}</header>
    <header>{{ __("Data pozwu") }}</header>

    @if(!$courts->isEmpty())
       @foreach($courts as $court)
           <data>
                <signature>{{ $court->signature }}</signature>
                <name>{{ $court->getCourtName() }}</name>
                <department>{{ $court->department }}</department>
                <address>{{ $court->getCourtAddress() }}</address>
                <status>{{ $court->getStatusName() }}</status>
                <model>{{ $court->getModeName() }}</model>
                <date>{{ $court->date }}</date>
           </data>
       @endforeach
   @endif
</export>
