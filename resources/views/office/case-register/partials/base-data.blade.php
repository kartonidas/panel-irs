<div class="pt-3">
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-end">
                {{ __("Podstawowe informacje") }}
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
            
                <div class="col-12 col-sm-4 mb-3">
                    <x-package-table-row label="Nazwa klienta" :value="$case->getCustomerName()" :route="route('office.customer.show', $case->customer_id)"/>
                </div>
                <div class="col-12 col-sm-4 mb-3">
                    <x-package-table-row label="Oznaczenie Klienta - numer sprawy klienta" :value="$case->customer_signature" />
                </div>
                <div class="col-12 col-sm-4 mb-3">
                    <x-package-table-row label="Oznaczenie RS" :value="$case->rs_signature" />
                </div>
                <div class="col-12 col-sm-6 mb-3">
                    <x-package-table-row label="Przeciwnik" :value="$case->opponent" />
                </div>
                <div class="col-12 col-sm-6 mb-3">
                    <x-package-table-row label="Adres" :value="\App\Libraries\Helper::getAddress($case->opponent_street, '', '', $case->opponent_zip, $case->opponent_city)" />
                </div>
                <div class="col-12 col-sm-3 mb-3">
                    <x-package-table-row label="PESEL" :value="$case->opponent_pesel ?? '-'" />
                </div>
                <div class="col-12 col-sm-3 mb-3">
                    <x-package-table-row label="REGON" :value="$case->opponent_regon ?? '-'" />
                </div>
                <div class="col-12 col-sm-3 mb-3">
                    <x-package-table-row label="NIP" :value="$case->opponent_nip ?? '-'" />
                </div>
                <div class="col-12 col-sm-3 mb-3">
                    <x-package-table-row label="KRS" :value="$case->opponent_krs ?? '-'" />
                </div>
                <div class="col-12 col-sm-6 mb-3">
                    <x-package-table-row label="Telefon" value="">
                        <x-slot:content>
                            @if(!empty($case->opponent_phone))
                                <a href="tel:{{ $case->opponent_phone }}">{{ $case->opponent_phone }}</a>
                            @endif
                        </x-slot:content>
                    </x-package-table-row>
                </div>
                <div class="col-12 col-sm-6 mb-3">
                    <x-package-table-row label="Adres e-mail" value="">
                        <x-slot:content>
                            @if(!empty($case->opponent_email))
                                <a href="mailto:{{ $case->opponent_email }}">{{ $case->opponent_email }}</a>
                            @endif
                        </x-slot:content>
                    </x-package-table-row>
                </div>
                <div class="col-12 col-sm-3 mb-3">
                    <x-package-table-row label="Stan sprawy" :value="$case->getStatusName()" />
                </div>
                <div class="col-12 col-sm-3 mb-3">
                    <x-package-table-row label="Zgon" :value="$case->death" :yesNo="true">
                        @if($case->death)
                            <small>({{ __("Data zgonu") }} : {{ $case->date_of_death }})</small>
                        @endif
                    </x-package-table-row>
                </div>
                <div class="col-12 col-sm-3 mb-3">
                    <x-package-table-row label="Upadłość" :value="$case->insolvency" :yesNo="true" />
                </div>
                <div class="col-12 col-sm-3 mb-3">
                    <x-package-table-row label="Obsługa zakończona" :value="$case->completed" :yesNo="true" />
                </div>
                
                <div class="col-12 col-sm-6 mb-3">
                    <x-package-table-row label="Komornik" :value="$case->baliff" :border="false" />
                </div>
                <div class="col-12 col-sm-6 mb-3">
                    <x-package-table-row label="Sąd" :value="$case->getCourtName()" :border="false" />
                </div>
            </div>
        </div>
    </div>
</div>
