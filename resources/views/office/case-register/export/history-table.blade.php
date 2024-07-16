{!! '<'.'?xml version="1.0"?>' !!}
<export>
    <header>{{ __("Czynność") }}</header>
    <header>{{ __("Opis") }}</header>
    <header>{{ __("Data wykonania") }}</header>

    @if(!$histories->isEmpty())
       @foreach($histories as $history)
           <data>
                <action>{{ $history->getActionName() }}</action>
                <description>{{ $history->description }}</description>
                <date>{{ $history->date }}</date>
           </data>
       @endforeach
   @endif
</export>
