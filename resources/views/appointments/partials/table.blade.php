<div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>{{ __('app.patient') }}</th>
                <th>{{ __('app.doctor') }}</th>
                <th>{{ __('app.service') }}</th>
                <th>{{ __('app.date') }}</th>
                <th>{{ __('app.status') }}</th>
                <th class="text-end">{{ __('app.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->patient?->name ?? __('app.not_available') }}</td>
                    <td>{{ $appointment->medecin?->name ?? __('app.not_available') }}</td>
                    <td>{{ $appointment->service?->name ?? __('app.not_available') }}</td>
                    <td>
                        {{ optional($appointment->appointment_date)->format('d/m/Y') }}
                        <div class="text-muted small">{{ $appointment->appointment_time }}</div>
                    </td>
                    <td>{{ __('app.statut_'.$appointment->statut) }}</td>
                    <td class="text-end">
                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary">
                            {{ __('app.show') }}
                        </a>
                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-outline-secondary">
                            {{ __('app.edit') }}
                        </a>
                        @if(auth()->user()->isAdmin() || auth()->user()->isPatient())
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger btn-delete"
                                data-url="{{ route('appointments.destroy', $appointment) }}">
                                {{ __('app.delete') }}
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        {{ __('app.no_appointments') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
