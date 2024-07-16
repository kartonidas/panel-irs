{!! '<'.'?xml version="1.0"?>' !!}
<export>
    <header>{{ __("Deklarowany termin wpłaty") }}</header>
    <header>{{ __("Wysokość deklarowanej raty") }}</header>

    @if(!$schedules->isEmpty())
       @foreach($schedules as $schedule)
           <data>
                <date>{{ $schedule->date }}</date>
                <amount>{{ amount($schedule->amount) }}</amount>
           </data>
       @endforeach
   @endif
</export>