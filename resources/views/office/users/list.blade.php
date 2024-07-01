@extends("office.layout-auth")

@section("title"){{ __("Pracownicy kancelarii") }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item active" aria-current="page">{{ __("Pracownicy kancelarii") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:users"])

    @if(\App\Models\OfficeUser::checkAccess("users:create", false))
        <div class="text-end mb-4">
            <a href="{{ route("office.user.create") }}" class="btn btn-primary btn-icon">{{ __("Dodaj pracownika") }} <i class="bi-plus"></i></a>
        </div>
    @endif

    <form method="GET" action="{{ route("office.users.filter") }}" class="mb-4">
        <div class="row mb-2 g-3 align-items-end">
            <div class="col">
                <label for="filterEmail" class="form-label mb-0">{{ __("Adres e-mail") }}</label>
                <input type="text" name="email" class="form-control" id="filterEmail" value="{{ $filter["email"] ?? "" }}">
            </div>
            <div class="col">
                <label for="filterName" class="form-label mb-0">{{ __("Nazwa") }}</label>
                <input type="text" name="name" class="form-control" id="filterName" value="{{ $filter["name"] ?? "" }}">
            </div>
            <div class="col col-2">
                <label for="filterBlock" class="form-label mb-0">{{ __("Pokaż") }}</label>
                <select name="block" class="form-select" id="filterBlock">
                    <option></option>
                    <option value="1" @if(($filter["block"] ?? "") == "1"){{ "selected" }}@endif>{{ __("Z blokadą") }}</option>
                    <option value="0" @if(($filter["block"] ?? "") == "0"){{ "selected" }}@endif>{{ __("Bez blokady") }}</option>
                </select>
            </div>
            <div class="col-auto">
                <a href="{{ route("office.users.filter.clear") }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                <button type="submit" class="btn btn-secondary">{{ __("Szukaj") }}</button>
            </div>
        </div>
    </form>

    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ route("office.users.sort", ["sort" => $sortColumns["email"]]) }}" class="{{ $sortColumns["class.email"] }}">
                                    {{ __("Adres e-mail") }}
                                </a>
                            </th>
                            <th>
                                <a href="{{ route("office.users.sort", ["sort" => $sortColumns["name"]]) }}" class="{{ $sortColumns["class.name"] }}">
                                    {{ __("Nazwa") }}
                                </a>
                            </th>
                            <th class="text-center" style="width: 120px">
                                <a href="{{ route("office.users.sort", ["sort" => $sortColumns["active"]]) }}" class="{{ $sortColumns["class.active"] }}">
                                    {{ __("Aktywny") }}
                                </a>
                            </th>
                            <th class="text-center" style="width: 150px">
                                <a href="{{ route("office.users.sort", ["sort" => $sortColumns["block"]]) }}" class="{{ $sortColumns["class.block"] }}">
                                    {{ __("Zablokowane") }}
                                </a>
                            </th>
                            <th style="width: 120px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$users->isEmpty())
                           @foreach($users as $user)
                               <tr>
                                    <td class="align-middle">
                                        {{ $user->email }}
                                        <div class="text-muted">
                                            <small>{{ $permissions[$user->office_permission_id] ?? "" }}</small>
                                        </div>
                                    </td>
                                    <td class="align-middle">{{ $user->name }}</td>
                                    <td class="align-middle text-center">
                                        @if($user->active)
                                            {{ __("Tak") }}
                                        @else
                                            {{ __("Nie") }}
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($user->block)
                                            <span class="text-danger fw-bolder">{{ __("Tak") }}</span>
                                            <div class="text-muted lh-1" style="white-space: normal">
                                                <small>{{ \App\Libraries\Data::getBlockReasons()[$user->block_reason] ?? $user->block_reason }}</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle text-end">
                                        @if(\App\Models\OfficeUser::checkAccess("users:update", false))
                                            @if($user->block)
                                                <span title="{{ __("Odblokuj konto") }}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#unblockUserAccount-{{ $user->id }}">
                                                        <i class="bi bi-lock"></i>
                                                    </a>
                                                </span>
                                                <div class="modal fade" id="unblockUserAccount-{{ $user->id }}" tabindex="-1" aria-labelledby="unblockUserAccount-{{ $user->id }}" aria-hidden="true">
                                                    <div class="modal-dialog text-center" style="white-space: normal">
                                                        <div class="modal-content">
                                                            <form method="POST" action="{{ route("office.user.unblock", $user->id) }}" class="validate">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="unblockUserAccount-{{ $user->id }}">{{ __("Odblokuj konto") }} <i class="bi bi-unlock"></i></h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                     {!! __("Dla konta o adresie e-mail: <b>:account</b> zostanie zdjęta blokada. Zalogowanie na to konto będzie możliwe. Odblokować konto?", ["account" => $user->email]) !!}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __("Anuluj") }}</button>
                                                                    <button type="submit" class="btn btn-sm btn-danger">{{ __("Odblokuj") }}</button>
                                                                </div>
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span title="{{ __("Zablokuj konto") }}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#blockUserAccount-{{ $user->id }}">
                                                        <i class="bi bi-unlock"></i>
                                                    </a>
                                                </span>
                                                <div class="modal fade" id="blockUserAccount-{{ $user->id }}" tabindex="-1" aria-labelledby="blockUserAccountLabel-{{ $user->id }}" aria-hidden="true">
                                                    <div class="modal-dialog text-center" style="white-space: normal">
                                                        <div class="modal-content">
                                                            <form method="POST" action="{{ route("office.user.block", $user->id) }}" class="validate">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="blockUserAccountLabel-{{ $user->id }}">{{ __("Zablokuj konto") }} <i class="bi bi-lock"></i></h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                     {!! __("Dla konta o adresie e-mail: <b>:account</b> zostanie nałożona blokada. Zalogowanie na to konto będzie niemożliwe. Zablokować konto?", ["account" => $user->email]) !!}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __("Anuluj") }}</button>
                                                                    <button type="submit" class="btn btn-sm btn-danger">{{ __("Zablokuj") }}</button>
                                                                </div>
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        <a href="{{ route("office.user.update", $user->id) }}" class="btn btn-sm btn-primary @if(!\App\Models\OfficeUser::checkAccess("users:update", false)){{ "disabled" }}@endif">
                                            <i class="bi-pencil"></i>
                                        </a>
           
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#removeUserModal-{{ $user->id }}" class="btn btn-sm btn-danger @if(!\App\Models\OfficeUser::checkAccess("users:update", false)){{ "disabled" }}@endif">
                                            <i class="bi-trash"></i>
                                        </a>
                                        @if(\App\Models\OfficeUser::checkAccess("users:delete", false))
                                            <div class="modal fade" id="removeUserModal-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                               <div class="modal-dialog text-start">
                                                   <div class="modal-content">
                                                       <form method="POST" action="{{ route("office.user.delete", $user->id) }}" class="validate">
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
                               <td colspan="5">{{ __("Brak rekordów") }}</td>
                           </tr>
                       @endif
                    </tbody>
                </table>
            </div>
        </div>
            
        @if($users->hasPages())
            <div class="card-footer clearfix card-footer-pagination">
                {!! $users->render("office.partials.pagination") !!}
            </div>
        @endif
    </div>
@endsection
