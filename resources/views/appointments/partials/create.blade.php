
@extends('layouts.app')

@section('title', __('app.new_appointment'))
@section('page-title', __('app.new_appointment'))

@section('content')

<div class="card border-0 shadow-sm"
     style="max-width:700px;margin:auto;border-radius:14px;overflow:hidden">

    <div class="card-body p-4">

        <h5 class="fw-bold mb-4 text-primary">
            <i class="fas fa-calendar-plus me-2"></i>
            {{ __('app.new_appointment') }}
        </h5>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('appointments.store') }}">
            @csrf

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label text-primary">
                        {{ __('app.patient') }}
                    </label>

                    <select name="patient_id" class="form-control" required>
                        <option value=""></option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}"
                                {{ auth()->user()->isPatient() && auth()->id()==$p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-primary">
                        {{ __('app.doctor') }}
                    </label>

                    <select name="medecin_id" class="form-control" required>
                        <option value="">medecins</option>
                        @foreach($medecins as $m)
                            <option value="{{ $m->id }}">
                                {{ $m->name }} ({{ $m->specialite }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-primary">
                        {{ __('app.service') }}
                    </label>

                    <select name="service_id" class="form-control" required>
                        <option value="">services</option>
                        @foreach($services as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->name }} ({{ $s->duree_minutes }} min)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-primary">
                        {{ __('app.date') }}
                    </label>

                    <input type="date"
                           name="appointment_date"
                           class="form-control"
                           min="{{ date('Y-m-d') }}"
                           value="{{ old('appointment_date') }}"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-primary">
                        {{ __('app.time') }}
                    </label>

                    <input type="time"
                           name="appointment_time"
                           class="form-control"
                           value="{{ old('appointment_time') }}"
                           required>
                </div>

                <div class="col-12">
                    <label class="form-label text-primary">
                        {{ __('app.remarks') }}
                    </label>

                    <textarea name="remarques"
                              class="form-control"
                              rows="3">{{ old('remarques') }}</textarea>
                </div>

            </div>

            <div class="d-flex gap-2 mt-4">

                <button class="btn btn-primary px-4">
                    <i class="fas fa-save me-1"></i>
                    {{ __('app.save') }}
                </button>

                <a href="{{ route('appointments.index') }}"
                   class="btn btn-outline-secondary px-4">
                    {{ __('app.cancel') }}
                </a>

            </div>

        </form>

    </div>

</div>

@endsection


<style>
    .form-control {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 9px;
        font-size: 0.9rem;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
    }

    .btn-primary {
        background: #0d6efd;
        border: none;
        border-radius: 10px;
    }

    .btn-primary:hover {
        background: #0b5ed7;
    }

    .btn-outline-secondary {
        border-radius: 10px;
    }

    .alert-danger {
        border-radius: 10px;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 500;
    }
</style>