@extends("office.layout-auth")

@section("title"){{ $court->name }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.courts") }}">{{ __("Baza sądów") }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $court->name }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:courts"])
    
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __("Sąd") }}</h3>
                        @if(\App\Models\OfficeUser::checkAccess("courts:update", false))
                            <a href="{{ route("office.court.update", $court->id) }}" class="btn btn-sm btn-primary">
                                {{ __("Edytuj") }}
                                <i class="bi bi-pencil"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-package-table-row label="Nazwa" :value="$court->name" />
                            <x-package-table-row label="Adres" :value="\App\Libraries\Helper::getAddress($court->street, '', '', $court->zip, $court->city, '<br/>')" :border="false" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-package-table-row label="Telefon" :value="nl2br($court->phone)" />
                            <x-package-table-row label="FAX" :value="nl2br($court->fax)" />
                            <x-package-table-row label="E-mail" :value="nl2br($court->email)" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection