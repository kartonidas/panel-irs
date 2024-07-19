<div class="tab-pane fade show @if($activeTab == "currencies"){{ "active" }}@endif" id="currencies" role="tabpanel">
    <form method="POST" action="{{ route("office.currencies.post") }}" class="validate">
        <div class="card-body">
            @foreach($currenciesTable as $table => $currencies)
                <h3>{{ $table }}</h3>
                <div class="row mb-2">
                    @foreach($currencies as $currency)
                        <div class="col-12 col-sm-4">
                            <input type="checkbox" name="currency[]" id="formCurrencySymbol{{ $currency->symbol }}" value="{{ $currency->symbol }}" @if($currency->active){{ "checked" }}@endif>
                            <label for="formCurrencySymbol{{ $currency->symbol }}" class="form-label">
                                <small>{{ $currency->name }} ({{ $currency->symbol }})</small>
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        <div class="card-footer">
            @include("office.partials.buttons", ["hideSaveAndEdit" => true])
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>