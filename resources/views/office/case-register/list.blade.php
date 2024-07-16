@extends("office.layout-auth")

@section("title"){{ __("Rejestr spraw") }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item active" aria-current="page">{{ __("Rejestr spraw") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:cases"])
    
    <div class="text-end mb-4">
        <a href="{{ route("office.case_register.create") }}" class="btn btn-primary btn-icon"><i class="bi bi-plus-lg"></i> {{ __("Dodaj") }}</a>
    </div>
    
    <form method="GET" action="{{ route("office.case_register.filter") }}" class="mb-4">
        <div class="row mb-2 g-3 align-items-end">
            <div class="col">
                <label for="filterCustomer" class="form-label mb-0">{{ __("Klient") }}</label>
                <select class="form-control form-select form-select-2" name="customer_id" id="filterCustomer" data-allow-clear="true" data-placeholder="">
                    <option></option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" @if($customer->id == ($filter["customer_id"] ?? "")){{ "selected" }}@endif>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filterCustomerSignature" class="form-label mb-0">{{ __("Oznaczenie klienta") }}</label>
                <input type="text" name="customer_signature" class="form-control" id="filterCustomerSignature" value="{{ $filter["customer_signature"] ?? "" }}">
            </div>
            <div class="col">
                <label for="filterRsSignature" class="form-label mb-0">{{ __("Oznaczenie RS") }}</label>
                <input type="text" name="rs_signature" class="form-control" id="filterRsSignature" value="{{ $filter["rs_signature"] ?? "" }}">
            </div>
            <div class="col">
                <label for="filterStatus" class="form-label mb-0">{{ __("Status sprawy") }}</label>
                <select type="text" name="status_id" class="form-select" id="filterStatus">
                    <option></option>
                    @foreach($caseStatuses as $statusId => $caseStatus)
                        <option value="{{ $statusId }}" @if(($filter["status_id"] ?? "") == $statusId){{ "selected" }}@endif>{{ $caseStatus }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <a href="{{ route("office.case_register.filter.clear") }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                <button type="submit" class="btn btn-secondary">{{ __("Szukaj") }}</button>
            </div>
        </div>
    </form>
        
    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="d-flex align-items-center justify-content-end mt-3 mb-1 me-3">
                @include("office.partials.pagination-size", ["currentSize" => $size, "route" => "office.case_register.size"])
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ route("office.case_register.sort", ["sort" => $sortColumns["customer_name"]]) }}" class="{{ $sortColumns["class.customer_name"] }}">
                                    {{ __("Klient") }}
                                </a>
                            </th>
                            <th>
                                <a href="{{ route("office.case_register.sort", ["sort" => $sortColumns["customer_signature"]]) }}" class="{{ $sortColumns["class.customer_signature"] }}">
                                    {{ __("Oznaczenie klienta") }}
                                </a>
                            </th>
                            <th>
                                <a href="{{ route("office.case_register.sort", ["sort" => $sortColumns["rs_signature"]]) }}" class="{{ $sortColumns["class.rs_signature"] }}">
                                    {{ __("Oznaczenie RS") }}
                                </a>
                            </th>
                            <th>
                                <a href="{{ route("office.case_register.sort", ["sort" => $sortColumns["opponent"]]) }}" class="{{ $sortColumns["class.opponent"] }}">
                                    {{ __("Dane przeciwnika") }}
                                </a>
                            </th>
                                
                            <th>
                                <a href="{{ route("office.case_register.sort", ["sort" => $sortColumns["opponent_pesel"]]) }}" class="{{ $sortColumns["class.opponent_pesel"] }}">
                                    {{ __("PESEL") }}
                                </a>
                            </th>
                            <th>
                                <a href="{{ route("office.case_register.sort", ["sort" => $sortColumns["opponent_regon"]]) }}" class="{{ $sortColumns["class.opponent_regon"] }}">
                                    {{ __("REGON") }}
                                </a>
                            </th>
                            <th>
                                <a href="{{ route("office.case_register.sort", ["sort" => $sortColumns["opponent_nip"]]) }}" class="{{ $sortColumns["class.opponent_nip"] }}">
                                    {{ __("NIP") }}
                                </a>
                            </th>
                            <th>
                                <a href="{{ route("office.case_register.sort", ["sort" => $sortColumns["opponent_krs"]]) }}" class="{{ $sortColumns["class.opponent_krs"] }}">
                                    {{ __("KRS") }}
                                </a>
                            </th>
                            <th>
                                <a href="{{ route("office.case_register.sort", ["sort" => $sortColumns["opponent_phone"]]) }}" class="{{ $sortColumns["class.opponent_phone"] }}">
                                    {{ __("Telefon") }}
                                </a>
                            </th>
                            <th>
                                <a href="{{ route("office.case_register.sort", ["sort" => $sortColumns["opponent_email"]]) }}" class="{{ $sortColumns["class.opponent_email"] }}">
                                    {{ __("Adres e-mail") }}
                                </a>
                            </th>
                            <th>{{ __("Ulica") }}</th>
                            <th>{{ __("Kod pocztowy") }}</th>
                            <th>{{ __("Miasto") }}</th>
                            <th>{{ __("Status sprawy") }}</th>
                            <th style="width: 120px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$cases->isEmpty())
                           @foreach($cases as $case)
                               <tr>
                                    <td class="align-middle">
                                        {{ $case->getCustomerName() }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->customer_signature }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->rs_signature }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->opponent }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->opponent_pesel }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->opponent_regon }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->opponent_nip }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->opponent_krs }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->opponent_phone }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->opponent_email }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->opponent_street }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->opponent_zip }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->opponent_city }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->getStatusName() }}
                                    </td>
                                    <td class="align-middle text-end">
                                        <a href="{{ route("office.case_register.show", $case->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi-search"></i>
                                        </a>
                                   </td>
                               </tr>
                           @endforeach
                       @else
                           <tr>
                               <td colspan="15">{{ __("Brak rekordów") }}</td>
                           </tr>
                       @endif
                    </tbody>
                </table>
            </div>
        </div>
            
        @if($cases->hasPages())
            <div class="card-footer clearfix card-footer-pagination">
                {!! $cases->render("office.partials.pagination") !!}
            </div>
        @endif
    </div>
@endsection