@extends("office.layout-auth")

@section("title"){{ __("Grupy uprawnień") }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item active" aria-current="page">{{ __("Grupy uprawnień") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "permissions"])
    
    @if(\App\Models\OfficeUser::checkAccess("permissions:create", false))
        <div class="text-end mb-4">
            <a href="{{ route("office.permission.create") }}" class="btn btn-primary btn-icon"><i class="bi bi-plus-lg"></i> {{ __("Dodaj") }}</a>
        </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 120px"></th>
                            <th>
                                <a href="{{ route("office.permissions.sort", ["sort" => $sortColumns["name"]]) }}" class="{{ $sortColumns["class.name"] }}">
                                    {{ __("Nazwa grupy") }}
                                </a>
                            </th>
                            <th style="width: 200px">{{ __("Rola") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$permissions->isEmpty())
                            @foreach($permissions as $permission)
                                <tr>
                                    <td class="align-middle text-start">
                                        <a href="{{ route("office.permission.update", $permission->id) }}" class="btn btn-sm btn-primary btn-icon @if(!\App\Models\OfficeUser::checkAccess("permissions:update", false)){{ "disabled" }}@endif" title="Edycja" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        @if($permission->canDelete() && \App\Models\OfficeUser::checkAccess("permissions:delete", false))
                                            <span data-bs-toggle="modal" data-bs-target="#removeGroupModal-{{ $permission->id }}">
                                                <span class="btn btn-sm btn-danger btn-icon" title="Usuń" data-bs-toggle="tooltip">
                                                    <i class="bi bi-x-lg"></i>
                                                </span>
                                            </span>
                                            <div class="modal fade" id="removeGroupModal-{{ $permission->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                               <div class="modal-dialog text-start">
                                                   <div class="modal-content">
                                                       <form method="POST" action="{{ route("office.permission.delete", $permission->id) }}" class="validate">
                                                           <div class="modal-header">
                                                               <h5 class="modal-title" id="exampleModalLabel">{{ __("Usuń uprawnienia") }}</h5>
                                                               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                           </div>
                                                           <div class="modal-body">
                                                               <p>{{ __("Wybrana grupa uprawnień zostanie usunięta. Kontynuować?") }}</p>
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
                                    <td class="align-middle">{{ $permission->name }}</td>
                                    <td class="align-middle">{{ $permission->getRoleName() }}</td>
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
        @if($permissions->hasPages())
            <div class="card-footer clearfix card-footer-pagination">
                {!! $permissions->render("office.partials.pagination") !!}
            </div>
        @endif
    </div>
@endsection
