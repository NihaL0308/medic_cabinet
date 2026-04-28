@extends('layouts.app')

@section('title', __('app.edit_appointment'))
@section('page-title', __('app.edit_appointment'))

@section('content')
<div class="card mx-auto" style="max-width: 760px;">
    <div class="card-body p-4">
        <h2 class="h4 mb-4">{{ __('app.edit_appointment') }}</h2>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('appointments.update', $appointment) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                @if(auth()->user()->isAdmin())
                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.patient') }}</label>
                        <select name="patient_id" class="form-select" required>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" @selected(old('patient_id', $appointment->patient_id) == $patient->id)>{{ $patient->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.doctor') }}</label>
                        <select name="medecin_id" class="form-select" required>
                            @foreach($medecins as $medecin)
                                <option value="{{ $medecin->id }}" @selected(old('medecin_id', $appointment->medecin_id) == $medecin->id)>{{ $medecin->name }} ({{ $medecin->specialite }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.service') }}</label>
                        <select name="service_id" class="form-select" required>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" @selected(old('service_id', $appointment->service_id) == $service->id)>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('app.date') }}</label>
                        <input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('app.time') }}</label>
                        <input type="time" name="appointment_time" class="form-control" value="{{ old('appointment_time', $appointment->appointment_time) }}" required>
                    </div>
                @else
                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.patient') }}</label>
                        <input type="text" class="form-control" value="{{ $appointment->patient?->name }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.doctor') }}</label>
                        <input type="text" class="form-control" value="{{ $appointment->medecin?->name }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.service') }}</label>
                        <input type="text" class="form-control" value="{{ $appointment->service?->name }}" disabled>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('app.date') }}</label>
                        <input type="text" class="form-control" value="{{ $appointment->appointment_date->format('d/m/Y') }}" disabled>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('app.time') }}</label>
                        <input type="text" class="form-control" value="{{ $appointment->appointment_time }}" disabled>
                    </div>
                @endif

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.status') }}</label>
                    @if(auth()->user()->isAdmin() || auth()->user()->isMedecin())
                        <select name="statut" class="form-select">
                            @foreach(['en_attente', 'confirme', 'annule', 'termine'] as $status)
                                <option value="{{ $status }}" @selected(old('statut', $appointment->statut) === $status)>{{ __('app.statut_'.$status) }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ __('app.statut_'.$appointment->statut) }}" disabled>
                        <small class="text-muted">{{ __('app.status_readonly') }}</small>
                    @endif
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    @if(auth()->user()->isAdmin())
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                    @else
                        <textarea class="form-control" rows="3" disabled>{{ $appointment->notes }}</textarea>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">{{ __('app.update') }}</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
