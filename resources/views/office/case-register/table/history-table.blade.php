@if(!$histories->isEmpty())
    @foreach($histories as $history)
        <tr>
            <td class="align-middle text-start">{{ $history->getActionName() }}</td>
            <td class="align-middle">{{ $history->description }}</td>
            <td class="align-middle text-center">{{ $history->date }}</td>
            <td class="align-middle">
                <a href="#" class="btn btn-sm btn-primary open-modal" data-modal="#historyModal" data-id="{{ $history->id }}" data-url="{{ route("office.case_register.history", [$case->id, $history->id], false) }}">
                    <i class="bi-pencil"></i>
                </a>

                <a href="#" data-bs-toggle="modal" data-bs-target="#removeHistoryModal-{{ $history->id }}" class="btn btn-sm btn-danger">
                    <i class="bi-trash"></i>
                </a>
                <div class="modal fade" id="removeHistoryModal-{{ $history->id }}" tabindex="-1" aria-labelledby="removeHistoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route("office.case_register.history.delete", [$case->id, $history->id]) }}" onsubmit="return Case.removeHistory(this);">
                                <div class="modal-header border-0">
                                    <h6 class="modal-title" id="removeHistoryModalLabel">{{ __("Usuń czynność") }}</h6>
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