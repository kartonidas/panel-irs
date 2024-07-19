@if(!$claims->isEmpty())
    @foreach($claims as $claim)
        <tr>
            <td class="align-middle text-start lh-1">
                {{ amount($claim->amount) }} {{ $claim->currency }}
                @if($claim->currency != "PLN")
                    <div class="text-muted">
                        <small>{{ amount($claim->amount_pln) }} PLN</small>
                    </div>
                @endif
            </td>
            <td class="align-middle text-center">{{ $claim->date }}</td>
            <td class="align-middle text-center">{{ $claim->due_date }}</td>
            <td class="align-middle">{{ $claim->mark }}</td>
            <td class="align-middle"style="white-space: initial">{{ $claim->description }}</td>
            <td class="align-middle">
                <a href="#" class="btn btn-sm btn-primary open-modal" data-modal="#claimModal" data-id="{{ $claim->id }}" data-url="{{ route("office.case_register.claim", [$case->id, $claim->id], false) }}">
                    <i class="bi-pencil"></i>
                </a>

                <a href="#" data-bs-toggle="modal" data-bs-target="#removeClaimModal-{{ $claim->id }}" class="btn btn-sm btn-danger">
                    <i class="bi-trash"></i>
                </a>
                <div class="modal fade" id="removeClaimModal-{{ $claim->id }}" tabindex="-1" aria-labelledby="removeClaimModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route("office.case_register.claim.delete", [$case->id, $claim->id]) }}" onsubmit="return Case.removeClaim(this);">
                                <div class="modal-header border-0">
                                    <h6 class="modal-title" id="removeClaimModalLabel">{{ __("Usuń roszczenie") }}</h6>
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