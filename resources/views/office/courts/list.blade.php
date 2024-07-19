@extends("office.layout-auth")

@section("title"){{ __("Sądy") }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item active" aria-current="page">{{ __("Sądy") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:courts"])
    
    @if(\App\Models\OfficeUser::checkAccess("courts:create", false))
        <div class="text-end mb-4">
            <a href="{{ route("office.court.create") }}" class="btn btn-primary btn-icon"><i class="bi bi-plus-lg"></i> {{ __("Dodaj") }}</a>
        </div>
    @endif
    
    <form method="GET" action="{{ route("office.courts.filter") }}" class="mb-4">
        <div class="row mb-2 g-3 align-items-end">
            <div class="col">
                <label for="filterName" class="form-label mb-0">{{ __("Nazwa") }}</label>
                <input type="text" name="name" class="form-control" id="filterName" value="{{ $filter["name"] ?? "" }}">
            </div>
            <div class="col">
                <label for="filterCity" class="form-label mb-0">{{ __("Miasto") }}</label>
                <input type="text" name="city" class="form-control" id="filterCity" value="{{ $filter["city"] ?? "" }}">
            </div>
            <div class="col-auto">
                <a href="{{ route("office.courts.filter.clear") }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                <button type="submit" class="btn btn-secondary">{{ __("Szukaj") }}</button>
            </div>
        </div>
    </form>
        
    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive ws-normal">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 120px"></th>
                            <th>
                                <a href="{{ route("office.courts.sort", ["sort" => $sortColumns["name"]]) }}" class="{{ $sortColumns["class.name"] }}">
                                    {{ __("Nazwa") }}
                                </a>
                            </th>
                            <th>{{ __("Adres") }}</th>
                            <th>{{ __("Telefon") }}</th>
                            <th>{{ __("Adres e-mail") }}</th>
                            <th>{{ __("FAX") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$courts->isEmpty())
                           @foreach($courts as $court)
                               <tr>
                                    <td class="align-middle text-start">
                                        <a href="{{ route("office.court.show", $court->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi-search"></i>
                                        </a>
           
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#removeCourtModal-{{ $court->id }}" class="btn btn-sm btn-danger @if(!\App\Models\OfficeUser::checkAccess("courts:update", false)){{ "disabled" }}@endif">
                                            <i class="bi-trash"></i>
                                        </a>
                                        @if(\App\Models\OfficeUser::checkAccess("courts:delete", false))
                                            <div class="modal fade" id="removeCourtModal-{{ $court->id }}" tabindex="-1" aria-labelledby="removeCourtModalLabel" aria-hidden="true">
                                               <div class="modal-dialog text-start">
                                                   <div class="modal-content">
                                                       <form method="POST" action="{{ route("office.court.delete", $court->id) }}" class="validate">
                                                           <div class="modal-header">
                                                               <h5 class="modal-title" id="removeCourtModalLabel">{{ __("Usuń sąd") }}</h5>
                                                               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                           </div>
                                                           <div class="modal-body">
                                                                {{ __("Wybrany sąd zostanie usunięty. Kontynuować?") }}
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
                                    <td class="align-middle">
                                        {{ $court->name }}
                                    </td>
                                    <td class="align-middle">
                                        {!! \App\Libraries\Helper::getAddress($court->street, '', '', $court->zip, $court->city, "<br/>") !!}
                                    </td>
                                    <td class="align-middle">
                                        {{ $court->phone }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $court->email }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $court->fax }}
                                    </td>
                               </tr>
                           @endforeach
                       @else
                           <tr>
                               <td colspan="6">{{ __("Brak rekordów") }}</td>
                           </tr>
                       @endif
                    </tbody>
                </table>
            </div>
        </div>
            
        @if($courts->hasPages())
            <div class="card-footer clearfix card-footer-pagination">
                {!! $courts->render("office.partials.pagination") !!}
            </div>
        @endif
    </div>
@endsection