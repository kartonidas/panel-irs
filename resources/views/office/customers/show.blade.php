@extends("office.layout-auth")

@section("title"){{ $customer->name }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.customers") }}">{{ __("Klienci") }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $customer->name }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:customers"])
    
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __("Firma") }}</h3>
                        @if(\App\Models\OfficeUser::checkAccess("customers:update", false))
                            <a href="{{ route("office.customer.update", $customer->id) }}" class="btn btn-sm btn-primary">
                                {{ __("Edytuj") }}
                                <i class="bi bi-pencil"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <x-package-table-row label="Nazwa" :value="$customer->name" />
                            <x-package-table-row label="Adres" :value="\App\Libraries\Helper::getAddress($customer->street, $customer->house_no, $customer->apartment_no, $customer->zip, $customer->city, '<br/>')" :border="false" />
                        </div>
                        <div class="col-12 col-md-4">
                            <x-package-table-row label="NIP" :value="$customer->nip" />
                            <x-package-table-row label="REGON" :value="$customer->regon" />
                            <x-package-table-row label="KRS" :value="$customer->krs" :border="false" />
                        </div>
                        <div class="col-12 col-md-4">
                            <x-package-table-row label="Numery spraw" :value="implode(', ', $customer->getAssignedCaseNumbers())" :border="false" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-7">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">{{ __("Użytkownicy konta") }}</h3>
                                @if(\App\Models\OfficeUser::checkAccess("customers:update", false))
                                    <a href="{{ route("office.customer.user.create", $customer->id) }}" class="btn btn-sm btn-primary">
                                        {{ __("Dodaj użytkownika") }}
                                        <i class="bi bi-plus"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __("Adres e-email / Nazwa") }}</th>
                                            <th class="text-center">{{ __("Aktywny") }}</th>
                                            <th class="text-center" style="width: 150px">{{ __("Zablokowane") }}</th>
                                            <th style="width:60px;"></th>
                                        </tr>
                                    </thead>
                                    @if(!$users->isEmpty())
                                        @foreach($users as $user)
                                            <tr>
                                                <td class="align-middle lh-sm">
                                                    <div>
                                                        {{ $user->email }}
                                                    </div>
                                                    <small>{{ $user->firstname }} {{ $user->lastname }}</small>
                                                </td>
                                                <td class="align-middle text-center">{{ $user->active ? __("TAK") : __("NIE") }}</td>
                                                <td class="align-middle text-center">
                                                    @if($user->block)
                                                        <span class="text-danger fw-bolder">{{ __("Tak") }}</span>
                                                        <div class="text-muted lh-1" style="white-space: normal">
                                                            <small>{{ \App\Libraries\Data::getBlockReasons()[$user->block_reason] ?? $user->block_reason }}</small>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-end">
                                                    @if(\App\Models\OfficeUser::checkAccess("customers:update", false))
                                                        @if($user->block)
                                                            <span title="{{ __("Odblokuj konto") }}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#unblockUserAccount-{{ $user->id }}">
                                                                    <i class="bi bi-lock"></i>
                                                                </a>
                                                            </span>
                                                            <div class="modal fade" id="unblockUserAccount-{{ $user->id }}" tabindex="-1" aria-labelledby="unblockUserAccount-{{ $user->id }}" aria-hidden="true">
                                                                <div class="modal-dialog text-center" style="white-space: normal">
                                                                    <div class="modal-content">
                                                                        <form method="POST" action="{{ route("office.customer.user.unblock", [$customer->id, $user->id]) }}" class="validate">
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
                                                                        <form method="POST" action="{{ route("office.customer.user.block", [$customer->id, $user->id]) }}" class="validate">
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
                                                
                                                
                                                
                                                
                                                
                                                
                                                    <a href="{{ route("office.customer.user.update", [$customer->id, $user->id]) }}" class="btn btn-sm btn-primary @if(!\App\Models\OfficeUser::checkAccess("customers:update", false)){{ "disabled" }}@endif">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-danger @if(!\App\Models\OfficeUser::checkAccess("customers:update", false)){{ "disabled" }}@endif" data-bs-toggle="modal" data-bs-target="#removeFirmUserModal-{{ $user->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                        
                                                    <div class="modal fade" id="removeFirmUserModal-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog text-start">
                                                            <div class="modal-content">
                                                                <form method="POST" action="{{ route("office.customer.user.delete", [$customer->id, $user->id]) }}" class="validate">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">{{ __("Usuń użytkownika") }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <div class="mb-1">
                                                                            <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
                                                                        </div>
                                                                         {!! __("Uzytkownik: :user zostanie usunięty. Kontynuować?", ["user" => "<b>" . $user->firstname . " " . $user->lastname . "</b>"]) !!}
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
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">
                                                {{ __("Brak dodatkowych użytkowników") }}
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">{{ __("Konfiguracja SFTP") }}</h3>
                                @if(\App\Models\OfficeUser::checkAccess("customers:update", false))
                                    <a href="{{ route("office.customer.sftp", $customer->id) }}" class="btn btn-sm btn-primary">
                                        {{ __("Skonfiguruj") }}
                                        <i class="bi bi-gear"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if(empty($sftp->host))
                                {{ __("Brak konfiguracji SFTP") }}
                            @else
                                {{ $sftp->login }}{{ "@" }}{{ $sftp->host }}:{{ $sftp->port }} {{ $sftp->path }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">{{ __("Widoczność elementów") }}</h3>
                                @if(\App\Models\OfficeUser::checkAccess("customers:update", false))
                                    <a href="{{ route("office.customer.visibility-elements", $customer->id) }}" class="btn btn-sm btn-primary">
                                        {{ __("Skonfiguruj") }}
                                        <i class="bi bi-gear"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach($fieldsVisibility as $i => $section)
                                <div class="fs-6 fw-bold border-bottom">{{ $section["label"] }}</div>
                                <div class="pt-1 pb-3">
                                    <small>
                                        @foreach($section["fields"] as $key => $label)
                                            <div class="d-inline-block me-2">
                                                @if(in_array($key, $customerVisibilityFields[$i] ?? []))
                                                    <i class="bi bi-check text-success"></i>
                                                @else
                                                    <i class="bi bi-x text-danger"></i>
                                                @endif
                                                {{ $label }}
                                            </div>
                                        @endforeach
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection