{!! '<'.'?xml version="1.0"?>' !!}
<export>
    <header>{{ __("Data wpłaty") }}</header>
    <header>{{ __("Kwota") }}</header>

    @if(!$payments->isEmpty())
       @foreach($payments as $payment)
           <data>
                <date>{{ $payment->date }}</date>
                <amount>{{ amount($payment->amount) }}</amount>
           </data>
       @endforeach
   @endif
</export>