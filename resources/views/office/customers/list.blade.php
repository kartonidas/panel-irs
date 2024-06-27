@extends("office.layout-auth")

@section("title"){{ __("Klienci") }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item active" aria-current="page">{{ __("Klienci") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:customers"])
    
    @if(\App\Models\OfficeUser::checkAccess("customers:create", false))
        <div class="text-end mb-4">
            <a href="{{ route("office.customer.create") }}" class="btn btn-primary btn-icon"><i class="bi bi-plus-lg"></i> {{ __("Dodaj") }}</a>
        </div>
    @endif
    
    <form method="GET" action="{{ route("office.filter", "office:customers") }}" class="mb-4">
        <div class="row mb-2 g-3 align-items-end">
            <div class="col">
                <label for="filterName" class="form-label mb-0">{{ __("Nazwa") }}</label>
                <input type="text" name="name" class="form-control" id="filterName" value="{{ $filter["name"] ?? "" }}">
            </div>
            <div class="col">
                <label for="filterNipRegonKrs" class="form-label mb-0">{{ __("NIP/Regon/KRS") }}</label>
                <input type="text" name="nip_regon_krs" class="form-control" id="filterNipRegonKrs" value="{{ $filter["nip_regon_krs"] ?? "" }}">
            </div>
            <div class="col-auto">
                <a href="{{ route("office.clear-filter", ["office:customers", "_back" => route("office.customers", [], false) ]) }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                <button type="submit" class="btn btn-secondary">{{ __("Szukaj") }}</button>
            </div>
        </div>
        <input type="hidden" name="_back" value="{{ route("office.customers") }}">
    </form>
        
    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __("Nazwa") }}</th>
                            <th>{{ __("Adres") }}</th>
                            <th>{{ __("NIP") }}</th>
                            <th>{{ __("Regon") }}</th>
                            <th>{{ __("KRS") }}</th>
                            <th class="text-center" style="width: 120px">{{ __("Aktywny") }}</th>
                            <th style="width: 120px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$customers->isEmpty())
                           @foreach($customers as $customer)
                               <tr>
                                    <td class="align-middle">
                                        {{ $customer->name }}
                                    </td>
                                    <td class="align-middle">
                                        {!! \App\Libraries\Helper::getAddress($customer->street, $customer->house_no, $customer->apartment_no, $customer->zip, $customer->city, "<br/>") !!}
                                    </td>
                                    <td class="align-middle">
                                        {{ $customer->nip }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $customer->regon }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $customer->krs }}
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($customer->active)
                                            {{ __("Tak") }}
                                        @else
                                            {{ __("Nie") }}
                                        @endif
                                    </td>
                                    <td class="align-middle text-end">
                                        <a href="{{ route("office.customer.show", $customer->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi-search"></i>
                                        </a>
           
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#removeCustomerModal-{{ $customer->id }}" class="btn btn-sm btn-danger @if(!\App\Models\OfficeUser::checkAccess("customers:update", false)){{ "disabled" }}@endif">
                                            <i class="bi-trash"></i>
                                        </a>
                                        @if(\App\Models\OfficeUser::checkAccess("customers:delete", false))
                                            <div class="modal fade" id="removeCustomerModal-{{ $customer->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                               <div class="modal-dialog text-start">
                                                   <div class="modal-content">
                                                       <form method="POST" action="{{ route("office.customer.delete", $customer->id) }}" class="validate">
                                                           <div class="modal-header">
                                                               <h5 class="modal-title" id="exampleModalLabel">{{ __("Usuń pracownika") }}</h5>
                                                               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                           </div>
                                                           <div class="modal-body">
                                                                {{ __("Wybrany pracownik zostanie usunięty. Kontynuować?") }}
                                                           </div>
                                                           <div class="modal-footer">
                                                               <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __("Anuluj") }}</button>
                                                               <button type="submit" class="btn btn-sm btn-danger">{{ __("Usuń") }}</button>
                                                           </div>
                                                           <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                       </form>
                                                   </div>
                                               </div>
                                           </div>
                                        @endif
                                   </td>
                               </tr>
                           @endforeach
                       @else
                           <tr>
                               <td colspan="7">{{ __("Brak rekordów") }}</td>
                           </tr>
                       @endif
                    </tbody>
                </table>
            </div>
        </div>
            
        @if($customers->hasPages())
            <div class="card-footer clearfix card-footer-pagination">
                {!! $customers->render("office.partials.pagination") !!}
            </div>
        @endif
    </div>
@endsection