@extends("office.layout-auth")

@section("title"){{ __("Słowniki") }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item active" aria-current="page">{{ __("Słowniki") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:dictionaries"])
    
    @if(\App\Models\OfficeUser::checkAccess("dictionaries:create", false))
        <div class="text-end mb-4">
            <a href="{{ route("office.dictionary.create") }}" class="btn btn-primary btn-icon"><i class="bi bi-plus-lg"></i> {{ __("Dodaj") }}</a>
        </div>
    @endif
    
    <form method="GET" action="{{ route("office.dictionaries.filter") }}" class="mb-4">
        <div class="row mb-2 g-3 align-items-end">
            <div class="col">
                <label for="filterType" class="form-label mb-0">{{ __("Rodzaj") }}</label>
                <select name="type" class="form-select" id="filterType">
                    <option></option>
                    @foreach($dictionaryTypes as $k => $type)
                        <option value="{{ $k }}" @if($k == ($filter["type"] ?? "")){{ "selected" }}@endif>{{ $type["name"] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <a href="{{ route("office.dictionaries.filter.clear") }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                <button type="submit" class="btn btn-secondary">{{ __("Szukaj") }}</button>
            </div>
        </div>
    </form>
    
    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 120px"></th>
                            <th style="width: 200px">{{ __("Typ") }}</th>
                            <th>{{ __("Wartość") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$dictionaries->isEmpty())
                            @foreach($dictionaries as $dictionary)
                                <tr>
                                    <td class="align-middle text-start">
                                        <a href="{{ route("office.dictionary.update", $dictionary->id) }}" class="btn btn-sm btn-primary btn-icon @if(!\App\Models\OfficeUser::checkAccess("dictionaries:update", false)){{ "disabled" }}@endif" title="Edycja" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        @if($dictionary->canDelete() && \App\Models\OfficeUser::checkAccess("dictionaries:delete", false))
                                            <span data-bs-toggle="modal" data-bs-target="#removeDictionaryModal-{{ $dictionary->id }}">
                                                <span class="btn btn-sm btn-danger btn-icon" title="Usuń" data-bs-toggle="tooltip">
                                                    <i class="bi bi-x-lg"></i>
                                                </span>
                                            </span>
                                            <div class="modal fade" id="removeDictionaryModal-{{ $dictionary->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                               <div class="modal-dialog text-start">
                                                   <div class="modal-content">
                                                       <form method="POST" action="{{ route("office.dictionary.delete", $dictionary->id) }}" class="validate">
                                                           <div class="modal-header">
                                                               <h5 class="modal-title" id="exampleModalLabel">{{ __("Usuń element") }}</h5>
                                                               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                           </div>
                                                           <div class="modal-body">
                                                               <p>{{ __("Wybrany element zostanie usunięty. Kontynuować?") }}</p>
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
                                        @else
                                            <a href="#" class="btn btn-sm btn-danger btn-icon disabled">
                                                <i class="bi bi-x-lg"></i>
                                            </a>
                                       @endif
                                    </td>
                                    <td class="align-middle">{{ $dictionary->getTypeLabel() }}</td>
                                    <td class="align-middle">{{ $dictionary->value }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">{{ __("Brak rekordów") }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @if($dictionaries->hasPages())
            <div class="card-footer clearfix card-footer-pagination">
                {!! $dictionaries->render("office.partials.pagination") !!}
            </div>
        @endif
    </div>
@endsection