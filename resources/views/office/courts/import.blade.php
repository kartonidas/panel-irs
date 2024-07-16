@extends("office.layout-auth")

@section("title"){{ "Nowy sąd" }}@endsection
@section("breadcrumbs")
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route("office.courts") }}">{{ __("Baza sądów") }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __("Import") }}</li>
@endsection

@section("content")
    @include("office.partials.errors")
    @include("office.partials.message", ["module" => "office:courts"])

    <form method="POST" action="{{ route("office.courts.import.post") }}" enctype="multipart/form-data">
        <div class="card card-primary card-outline mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="file" class="form-label">{{ __("Plik XLSX") }}*</label>
                        <input type="file" name="xls" class="form-control" id="file">
                        <small class="input-error-info"></small>
                        <div class="form-text">
                            {{ __("Plik powinien zawierać kolumny: nazwa sądu, ulica, kod pocztowy, miejscowość, telefon, fax, email. Bez nagłówka, plik *.xlsx.") }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @include("office.partials.buttons", ["back" => route("office.courts")])
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
@endsection
