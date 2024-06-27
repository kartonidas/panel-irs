<nav class="mt-2">
    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
        @if(\App\Models\OfficeUser::checkAccess("customers:list", false))
            <li class="nav-item">
                <a href="{{ route("office.case_register") }}" class="nav-link @if(($activeMenuItem ?? "") == "case_register"){{ "active" }}@endif"> <i class="nav-icon bi bi-briefcase"></i>
                    <p>{{ __("Rejestr spraw") }}</p>
                </a>
            </li>
        @endif
        @if(\App\Models\OfficeUser::checkAccess("customers:list", false))
            <li class="nav-item">
                <a href="{{ route("office.customers") }}" class="nav-link @if(($activeMenuItem ?? "") == "customers"){{ "active" }}@endif"> <i class="nav-icon bi bi-people"></i>
                    <p>{{ __("Klienci") }}</p>
                </a>
            </li>
        @endif
        @if(\App\Models\OfficeUser::checkAccess("users:list", false) || \App\Models\OfficeUser::checkAccess("permissions:list", false) || \App\Models\OfficeUser::checkAccess("dictionaries:list", false))
            <li class="nav-header">{{ __("ADMINISTRACJA") }}</li>
            @if(\App\Models\OfficeUser::checkAccess("dictionaries:list", false))
                <li class="nav-item">
                    <a href="{{ route("office.dictionaries") }}" class="nav-link @if(($activeMenuItem ?? "") == "dictionaries"){{ "active" }}@endif"> <i class="nav-icon bi bi-folder"></i>
                        <p>{{ __("Słowniki") }}</p>
                    </a>
                </li>
            @endif
            @if(\App\Models\OfficeUser::checkAccess("users:list", false))
                <li class="nav-item">
                    <a href="{{ route("office.users") }}" class="nav-link @if(($activeMenuItem ?? "") == "users"){{ "active" }}@endif"> <i class="nav-icon bi bi-person-plus"></i>
                        <p>{{ __("Pracownicy kancelarii") }}</p>
                    </a>
                </li>
            @endif
            @if(\App\Models\OfficeUser::checkAccess("permissions:list", false))
                <li class="nav-item">
                    <a href="{{ route("office.permissions") }}" class="nav-link @if(($activeMenuItem ?? "") == "permissions"){{ "active" }}@endif"> <i class="nav-icon bi bi-person-lock"></i>
                        <p>{{ __("Grupy uprawnień") }}</p>
                    </a>
                </li>
            @endif
        @endif
        
        <li class="nav-item">
            <a href="{{ route("office.profile") }}" class="nav-link @if(($activeMenuItem ?? "") == "profile"){{ "active" }}@endif"> <i class="nav-icon bi bi-person-gear"></i>
                <p>{{ __("Mój profil") }}</p>
            </a>
        </li>
    </ul>
</nav>