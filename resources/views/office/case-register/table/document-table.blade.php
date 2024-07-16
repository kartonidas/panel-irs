@if(!$documents->isEmpty())
    @foreach($documents as $document)
        <tr>
            <td class="align-middle text-start">{{ $document->date }}</td>
            <td class="align-middle">{{ $document->name }}</td>
            <td class="align-middle">
                {{ $document->origfile }}
            </td>
            <td class="align-middle">
                <a href="{{ route("office.case_register.document.download", [$case->id, $document->id]) }}" class="btn btn-icon btn-sm btn-secondary @if(!$hasSftp){{ "disabled" }}@endif"><i class="bi bi-download"></i></a>
                    
                <a href="#" class="btn btn-sm btn-primary open-modal" data-modal="#documentModal" data-id="{{ $document->id }}" data-url="{{ route("office.case_register.document", [$case->id, $document->id], false) }}" data-callback="Case.editDocument">
                    <i class="bi-pencil"></i>
                </a>

                <a href="#" data-bs-toggle="modal" data-bs-target="#removeDocumentModal-{{ $document->id }}" class="btn btn-sm btn-danger">
                    <i class="bi-trash"></i>
                </a>
                <div class="modal fade" id="removeDocumentModal-{{ $document->id }}" tabindex="-1" aria-labelledby="removeDocumentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route("office.case_register.document.delete", [$case->id, $document->id]) }}" onsubmit="return Case.removeDocument(this);">
                                <div class="modal-header border-0">
                                    <h6 class="modal-title" id="removeDocumentModalLabel">{{ __("Usuń dokument") }}</h6>
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