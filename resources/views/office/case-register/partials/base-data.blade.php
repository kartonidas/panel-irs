<div class="p-3 border-start border-bottom border-end">
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-end">
                @if(\App\Models\OfficeUser::checkAccess("customers:update", false))
                    <a href="{{ route("office.case_register.update", $case->id) }}" class="btn btn-sm btn-primary">
                        {{ __("Edytuj") }}
                        <i class="bi bi-pencil"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-4">
                <div class="col-12 mb-3">
                    <x-package-table-row label="Nazwa klienta" :value="$case->customer_name" />
                    <x-package-table-row label="Oznaczenie Klienta - numer sprawy klienta" :value="$case->customer_signature" />
                    <x-package-table-row label="Oznaczenie RS" :value="$case->rs_signature" />
                    <x-package-table-row label="Przeciwnik" :value="$case->opponent" />
                    <x-package-table-row label="Stan sprawy" :value="$case->getStatusName()" />
                    <x-package-table-row label="Zgon" :value="$case->death" :yesNo="true" />
                    @if($case->death)
                        <x-package-table-row label="Data zgonu" :value="$case->date_of_death" />
                    @endif
                    <x-package-table-row label="Upadłość" :value="$case->insolvency" :yesNo="true" />
                    <x-package-table-row label="Obsługa zakończona" :value="$case->completed" :yesNo="true" />
                    <x-package-table-row label="Komornik" :value="$case->baliff" />
                    <x-package-table-row label="Sąd" :value="$case->court" :border="false" />
                </div>
            </div>
        </div>
    </div>
</div>
