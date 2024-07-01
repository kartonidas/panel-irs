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
                <label for="filterCustomerSignature" class="form-label mb-0">{{ __("Oznaczenie klienta") }}</label>
                <input type="text" name="customer_signature" class="form-control" id="filterCustomerSignature" value="{{ $filter["customer_signature"] ?? "" }}">
            </div>
            <div class="col">
                <label for="filterRsSignature" class="form-label mb-0">{{ __("Oznaczenie RS") }}</label>
                <input type="text" name="rs_signature" class="form-control" id="filterRsSignature" value="{{ $filter["rs_signature"] ?? "" }}">
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
                            <th style="width: 120px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$cases->isEmpty())
                           @foreach($cases as $case)
                               <tr>
                                    <td class="align-middle">
                                        {{ $case->customer_signature }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->rs_signature }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $case->opponent }}
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
                               <td colspan="4">{{ __("Brak rekordów") }}</td>
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