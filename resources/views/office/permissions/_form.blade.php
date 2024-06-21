<div class="row">
    <div class="col-12 col-md-6 mb-3">
        <label for="formName" class="form-label">{{ __("Nazwa") }}*</label>
        <input type="text" id="formName" name="name" class="form-control" value="{{ $form["name"] ?? "" }}" data-validate="required">
        <small class="input-error-info"></small>
    </div>
    <div id="col-role" class="col-12 @if(($form["role"] ?? "") == \App\Models\OfficePermission::ROLE_ADMIN){{ "col-md-3" }}@else{{ "col-md-6" }}@endif col-md-3 mb-3">
        <label for="formRole" class="form-label">{{ __("Rola") }}*</label>
        <select class="form-select" name="role" id="formRole" onchange="Permission.changeRole(this);" data-validate="required">
            <option></option>
            @foreach($roles as $role => $roleLabel)
                <option value="{{ $role }}" @if(($form["role"] ?? "") == $role){{ "selected" }}@endif>{{ $roleLabel }}</option>
            @endforeach
        </select>
        <small class="input-error-info"></small>
    </div>
        
    <div id="col-role-admin-permission_type" class="col-12 col-md-3 mb-3 @if(($form["role"] ?? "") != \App\Models\OfficePermission::ROLE_ADMIN){{ "d-none" }}@endif role-admin">
        <label for="formAdminPermissionType" class="form-label">{{ __("Rodzaj uprawnień") }}*</label>
        <select class="form-select" name="admin_permission_type" id="formAdminPermissionType" onchange="Permission.changeAdminPermissionType(this);">
            @foreach($adminPermissionTypes as $adminPermissionType => $adminPermissionName)
                <option value="{{ $adminPermissionType }}" @if(($form["admin_permission_type"] ?? "") == $adminPermissionType){{ "selected" }}@endif>{{ $adminPermissionName }}</option>
            @endforeach
        </select>
        <small class="input-error-info"></small>
    </div>
    <div class="col col-permissions @if(!in_array($form["role"] ?? "", [\App\Models\OfficePermission::ROLE_ADMIN, \App\Models\OfficePermission::ROLE_EMPLOYEE]) || (($form["role"] ?? "") == \App\Models\OfficePermission::ROLE_ADMIN && ($form["admin_permission_type"] ?? "") == \App\Models\OfficePermission::ADMIN_PERMISSION_TYPE_FULL)){{ "d-none" }}@endif">
        <div class="fs-5">{{ __("Uprawnienia") }}</div>
        
        <div class="text-muted role-admin @if(($form["role"] ?? "") != \App\Models\OfficePermission::ROLE_ADMIN){{ "d-none" }}@endif">
            <small>
                {{ __("Administrator posiada pełne uprawnienia pracownika plus dostęp do wskazanych poniżej modułów.") }}
            </small>
        </div>
            
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __("Moduł") }}</th>
                        <th style="width: 18%">
                            <div class="form-check m-0">
                                <input class="form-check-input" id="groupCheckList" type="checkbox" onclick="Permission.checkGroup(this, 'list')">
                                <label class="form-check-label" for="groupCheckList" style="font-weight: 500">
                                    {{ __("Lista") }}
                                </label>
                            </div>
                        </th>
                        <th style="width: 18%">
                            <div class="form-check m-0">
                                <input class="form-check-input" id="groupCheckCreate" type="checkbox" onclick="Permission.checkGroup(this, 'create')">
                                <label class="form-check-label" for="groupCheckCreate" style="font-weight: 500">
                                    {{ __("Dodawanie") }}
                                </label>
                            </div>
                        </th>
                        <th style="width: 18%">
                            <div class="form-check m-0">
                                <input class="form-check-input" id="groupCheckUpdate" type="checkbox" onclick="Permission.checkGroup(this, 'update')">
                                <label class="form-check-label" for="groupCheckUpdate" style="font-weight: 500">
                                    {{ __("Modyfikacja") }}
                                </label>
                            </div>
                        </th>
                        <th style="width: 18%">
                            <div class="form-check m-0">
                                <input class="form-check-input" id="groupCheckDelete" type="checkbox" onclick="Permission.checkGroup(this, 'delete')">
                                <label class="form-check-label" for="groupCheckDelete" style="font-weight: 500">
                                    {{ __("Usuwanie") }}
                                </label>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody id="permissions-admin" class="permissions permissions-admin @if(($form["role"] ?? "") != \App\Models\OfficePermission::ROLE_ADMIN){{ "d-none" }}@endif">
                    @foreach($permissions["admin"] as $perm => $item)
                        <tr>
                            <td>
                                {{ $item["module"] }}
                            </td>
                            <td>
                                @if(in_array("list", $item["operation"]))
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" value="{{ $perm }}:list" name="permissions[admin][]" @if(in_array($perm . ":list", $form["permissions"] ?? [])){{ "checked" }}@endif>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if(in_array("create", $item["operation"]))
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" value="{{ $perm }}:create" name="permissions[admin][]" @if(in_array($perm . ":create", $form["permissions"] ?? [])){{ "checked" }}@endif>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if(in_array("update", $item["operation"]))
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" value="{{ $perm }}:update" name="permissions[admin][]" @if(in_array($perm . ":update", $form["permissions"] ?? [])){{ "checked" }}@endif>
                                     </div>
                                @endif
                            </td>
                            <td>
                                @if(in_array("delete", $item["operation"]))
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" value="{{ $perm }}:delete" name="permissions[admin][]" @if(in_array($perm . ":delete", $form["permissions"] ?? [])){{ "checked" }}@endif>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tbody id="permissions-employee" class="permissions permissions-employee @if(($form["role"] ?? "") != \App\Models\OfficePermission::ROLE_EMPLOYEE){{ "d-none" }}@endif">
                    @foreach($permissions["employee"] as $perm => $item)
                        <tr>
                            <td>
                                {{ $item["module"] }}
                            </td>
                            <td>
                                @if(in_array("list", $item["operation"]))
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" value="{{ $perm }}:list" name="permissions[employee][]" @if(in_array($perm . ":list", $form["permissions"] ?? [])){{ "checked" }}@endif>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if(in_array("create", $item["operation"]))
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" value="{{ $perm }}:create" name="permissions[employee][]" @if(in_array($perm . ":create", $form["permissions"] ?? [])){{ "checked" }}@endif>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if(in_array("update", $item["operation"]))
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" value="{{ $perm }}:update" name="permissions[employee][]" @if(in_array($perm . ":update", $form["permissions"] ?? [])){{ "checked" }}@endif>
                                     </div>
                                @endif
                            </td>
                            <td>
                                @if(in_array("delete", $item["operation"]))
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" value="{{ $perm }}:delete" name="permissions[employee][]" @if(in_array($perm . ":delete", $form["permissions"] ?? [])){{ "checked" }}@endif>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
