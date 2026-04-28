@extends('layouts.app')

@section('title', __('app.appointments'))
@section('page-title', __('app.appointments'))

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="h4 mb-1">{{ __('app.appointments') }}</h2>
        <p class="text-muted mb-0">{{ __('app.search_placeholder') }}</p>
    </div>

    @if(auth()->user()->isAdmin() || auth()->user()->isPatient())
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAppointmentModal">
            {{ __('app.new_appointment') }}
        </button>
    @endif
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="input-group">
            <span class="input-group-text bg-white">
                <i class="fas fa-search text-primary"></i>
            </span>
            <input type="text" id="searchInput" class="form-control" placeholder="{{ __('app.search_placeholder') }}">
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div id="appointmentsTable">
            @include('appointments.partials.table', ['appointments' => $appointments])
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin() || auth()->user()->isPatient())
    <div class="modal fade" id="createAppointmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ __('app.new_appointment') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('appointments.store') }}">
                    @csrf
                    <div class="modal-body">
                        @if($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif

                        <div class="row g-3">
                            @if(auth()->user()->isPatient())
                                <input type="hidden" name="patient_id" value="{{ auth()->id() }}">
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('app.patient') }}</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('app.patient') }}</label>
                                    <select name="patient_id" class="form-select" required>
                                        <option value="">{{ __('app.patient') }}</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>{{ $patient->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <label class="form-label">{{ __('app.doctor') }}</label>
                                <select name="medecin_id" class="form-select" required>
                                    <option value="">{{ __('app.doctor') }}</option>
                                    @foreach($medecins as $medecin)
                                        <option value="{{ $medecin->id }}" @selected(old('medecin_id') == $medecin->id)>{{ $medecin->name }} ({{ $medecin->specialite }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('app.service') }}</label>
                                <select name="service_id" class="form-select" required>
                                    <option value="">{{ __('app.service') }}</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">{{ __('app.date') }}</label>
                                <input type="date" name="appointment_date" class="form-control" min="{{ now()->format('Y-m-d') }}" value="{{ old('appointment_date') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">{{ __('app.time') }}</label>
                                <input type="time" name="appointment_time" class="form-control" value="{{ old('appointment_time') }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">{{ __('app.notes') }}</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">{{ __('app.confirm_delete') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">{{ __('app.delete_warning') }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">{{ __('app.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const appointmentSearch = document.getElementById('searchInput');

function bindAppointmentDeleteButtons() {
    document.querySelectorAll('.btn-delete').forEach((button) => {
        button.addEventListener('click', function () {
            document.getElementById('deleteForm').action = this.dataset.url;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });
}

if (appointmentSearch) {
    let timer;
    appointmentSearch.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            const url = new URL('{{ route('appointments.index') }}', window.location.origin);
            url.searchParams.set('search', this.value);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then((response) => response.text())
                .then((html) => {
                    document.getElementById('appointmentsTable').innerHTML = html;
                    bindAppointmentDeleteButtons();
                });
        }, 300);
    });
}

bindAppointmentDeleteButtons();

@if($errors->any() && (auth()->user()->isAdmin() || auth()->user()->isPatient()))
new bootstrap.Modal(document.getElementById('createAppointmentModal')).show();
@endif
</script>
@endpush
