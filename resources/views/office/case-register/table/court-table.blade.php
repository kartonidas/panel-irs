@if(!$courts->isEmpty())
    @foreach($courts as $court)
        <tr>
            <td class="align-middle text-start">{{ $court->signature }}</td>
            <td class="align-middle">
                {{ $court->getCourtName() }}
                <div class="lh-1 mt-1">
                    <small>
                        {{ $court->department }}
                        <br/>
                        <i class="text-muted">{{ $court->getCourtAddress() }}</i>
                    </small>
                </div>
                    
            </td>
            <td class="align-middle">{{ $court->getStatusName() }}</td>
            <td class="align-middle">{{ $court->getModeName() }}</td>
            <td class="align-middle">{{ $court->date }}</td>
            <td class="align-middle">
                <a href="#" class="btn btn-sm btn-primary open-modal" data-modal="#courtModal" data-id="{{ $court->id }}" data-url="{{ route("office.case_register.court", [$case->id, $court->id], false) }}" data-callback="Case.afterCourtModalOpen">
                    <i class="bi-pencil"></i>
                </a>

                <a href="#" data-bs-toggle="modal" data-bs-target="#removeCourtModal-{{ $court->id }}" class="btn btn-sm btn-danger">
                    <i class="bi-trash"></i>
                </a>
                <div class="modal fade" id="removeCourtModal-{{ $court->id }}" tabindex="-1" aria-labelledby="removeCourtModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route("office.case_register.court.delete", [$case->id, $court->id]) }}" onsubmit="return Case.removeCourt(this);">
                                <div class="modal-header border-0">
                                    <h6 class="modal-title" id="removeCourtModalLabel">{{ __("Usuń postępowanie sądowe") }}</h6>
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
        <td colspan="6">{{ __("Brak rekordów") }}</td>
    </tr>
@endif