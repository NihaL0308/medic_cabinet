@extends('layouts.app')

@section('title', __('app.new_appointment'))
@section('page-title', __('app.new_appointment'))

@section('content')
<div class="card mx-auto" style="max-width: 760px;">
    <div class="card-body p-4">
        <h2 class="h4 mb-4">{{ __('app.new_appointment') }}</h2>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('appointments.store') }}">
            @csrf
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

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">{{ __('app.save') }}</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
