@if(!$caseSelectedAccess->isEmpty())
    @foreach($caseSelectedAccess as $access)
        <tr>
            <td class="align-middle">{{ $access->getCustomerName() }}</td>
            <td class="align-middle">{{ $access->getTypeName() }}</td>
            <td class="align-middle">
                @if($access->type == \App\Models\OfficeUsersCaseAccess::CASE_ACCESS_SELECTED)
                    {{ implode(", ", $access->getSelectedCaseNumbers()) }}
                @else
                    -
                @endif
            </td>
            <td class="align-middle">
                <a href="#" class="btn btn-sm btn-primary open-modal" data-modal="#accessModal" data-id="{{ $access->id }}" data-url="{{ route("office.user.selected_case_access.access", [$user->id, $access->id], false) }}" data-callback="User.afterAccessModalOpen">
                    <i class="bi-pencil"></i>
                </a>

                <a href="#" data-bs-toggle="modal" data-bs-target="#removeAccessModal-{{ $access->id }}" class="btn btn-sm btn-danger">
                    <i class="bi-trash"></i>
                </a>
                <div class="modal fade" id="removeAccessModal-{{ $access->id }}" tabindex="-1" aria-labelledby="removeAccessModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route("office.user.selected_case_access.access.delete", [$user->id, $access->id]) }}" onsubmit="return User.removeAccess(this);">
                                <div class="modal-header border-0">
                                    <h6 class="modal-title" id="removeAccessModalLabel">{{ __("Usuń dostęp") }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center text-primary pb-2 fs-5 ">
                                    <p>{{ __("Wybrany wpis zostanie usunięty. Kontynuować?") }}</p>
                                </div>
                                <div class="modal-footer border-0 pb-0 d-flex gap-2 pb-3">
                                    <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">{{ __("Anuluj") }}</button>
                                    <button type="submit" class="btn btn-primary flex-fill">{{ __("Usuń") }}</button>
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
        <td colspan="4">{{ __("Brak rekordów") }}</td>
    </tr>
@endif
