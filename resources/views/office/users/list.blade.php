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

    <form method="GET" action="{{ route("office.filter", "office:users") }}" class="mb-4">
        <div class="row mb-2 g-3 align-items-end">
            <div class="col">
                <label for="filterEmail" class="form-label mb-0">{{ __("Adres e-mail") }}</label>
                <input type="text" name="email" class="form-control" id="filterEmail" value="{{ $filter["email"] ?? "" }}">
            </div>
            <div class="col">
                <label for="filterName" class="form-label mb-0">{{ __("Nazwa") }}</label>
                <input type="text" name="name" class="form-control" id="filterName" value="{{ $filter["name"] ?? "" }}">
            </div>
            <div class="col-auto">
                <a href="{{ route("office.clear-filter", ["office:users", "_back" => route("office.users", [], false) ]) }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                <button type="submit" class="btn btn-secondary">{{ __("Szukaj") }}</button>
            </div>
        </div>
        <input type="hidden" name="_back" value="{{ route("office.users") }}">
    </form>

    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                           <th>{{ __("Adres e-mail") }}</th>
                           <th>{{ __("Nazwa") }}</th>
                           <th class="text-center" style="width: 120px">{{ __("Aktywny") }}</th>
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
                                    <td class="align-middle text-end">
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
                               <td colspan="4">{{ __("Brak rekordów") }}</td>
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
