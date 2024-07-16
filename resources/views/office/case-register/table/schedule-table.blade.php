@if(!$schedules->isEmpty())
    @foreach($schedules as $schedule)
        <tr>
            <td class="align-middle text-start">{{ $schedule->date }}</td>
            <td class="align-middle text-end">{{ amount($schedule->amount) }}</td>
            <td class="align-middle">
                <a href="#" class="btn btn-sm btn-primary open-modal" data-modal="#scheduleModal" data-id="{{ $schedule->id }}" data-url="{{ route("office.case_register.schedule", [$case->id, $schedule->id], false) }}">
                    <i class="bi-pencil"></i>
                </a>

                <a href="#" data-bs-toggle="modal" data-bs-target="#removeScheduleModal-{{ $schedule->id }}" class="btn btn-sm btn-danger">
                    <i class="bi-trash"></i>
                </a>
                <div class="modal fade" id="removeScheduleModal-{{ $schedule->id }}" tabindex="-1" aria-labelledby="removeScheduleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route("office.case_register.schedule.delete", [$case->id, $schedule->id]) }}" onsubmit="return Case.removeSchedule(this);">
                                <div class="modal-header border-0">
                                    <h6 class="modal-title" id="removeScheduleModalLabel">{{ __("Usuń harmonogram") }}</h6>
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
        <td colspan="3">{{ __("Brak rekordów") }}</td>
    </tr>
@endif