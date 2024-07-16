{!! '<'.'?xml version="1.0"?>' !!}
<export>
    <header>{{ __("Roszczenie") }}</header>
    <header>{{ __("Data wystawienia") }}</header>
    <header>{{ __("Termin wymagalności") }}</header>
    <header>{{ __("Oznaczenie roszczenia") }}</header>
    <header>{{ __("Opis") }}</header>

    @if(!$claims->isEmpty())
       @foreach($claims as $claim)
           <data>
                <amount>{{ amount($claim->amount) }}</amount>
                <date>{{ $claim->date }}</date>
                <due_date>{{ $claim->due_date }}</due_date>
                <mark>{{ $claim->mark }}</mark>
                <description>{{ $claim->description }}</description>
           </data>
       @endforeach
   @endif
</export>
