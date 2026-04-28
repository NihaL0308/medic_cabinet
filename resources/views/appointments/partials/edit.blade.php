@extends('layouts.app')

@section('title', __('app.edit_appointment'))
@section('page-title', __('app.edit_appointment'))

@section('content')

<div class="card border-0 shadow-sm"
     style="max-width:700px;margin:auto;border-radius:14px;overflow:hidden">

    <div class="card-body p-4">

        <h5 class="fw-bold mb-4 text-primary">
            <i class="fas fa-calendar-edit me-2"></i>
            {{ __('app.edit_appointment') }}
        </h5>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('appointments.update', $appointment) }}">
            @csrf @method('PUT')

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label text-primary">
                        {{ __('app.doctor') }}
                    </label>

                    <select name="medecin_id" class="form-control">
                        @foreach($medecins as $m)
                            <option value="{{ $m->id }}"
                                {{ $appointment->medecin_id == $m->id ? 'selected' : '' }}>
                                {{ $m->name }} ({{ $m->specialite }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-primary">
                        {{ __('app.service') }}
                    </label>

                    <select name="service_id" class="form-control">
                        @foreach($services as $s)
                            <option value="{{ $s->id }}"
                                {{ $appointment->service_id == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
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
                           value="{{ $appointment->appointment_date->format('Y-m-d') }}"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-primary">
                        {{ __('app.time') }}
                    </label>

                    <input type="time"
                           name="appointment_time"
                           class="form-control"
                           value="{{ $appointment->appointment_time }}"
                           required>
                </div>

                {{-- STATUS --}}
                @if(auth()->user()->isMedecin() || auth()->user()->isAdmin())

                    <div class="col-md-6">
                        <label class="form-label text-primary">
                            {{ __('app.status') }}
                        </label>

                        <select name="statut" class="form-control">
                            @foreach(['en_attente','confirme','annule','termine'] as $s)
                                <option value="{{ $s }}"
                                    {{ $appointment->statut == $s ? 'selected' : '' }}>
                                    {{ __('app.statut_'.$s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                @else

                    <input type="hidden" name="statut" value="{{ $appointment->statut }}">

                    <div class="col-md-6">
                        <label class="form-label text-primary">
                            {{ __('app.status') }}
                        </label>

                        <div class="form-control bg-light text-muted">
                            {{ __('app.statut_'.$appointment->statut) }}
                        </div>

                        <small class="text-muted">
                            {{ __('app.status_readonly') }}
                        </small>
                    </div>

                @endif

                <div class="col-12">
                    <label class="form-label text-primary">
                        {{ __('app.notes') }}
                    </label>

                    <textarea name="notes"
                              class="form-control"
                              rows="3">{{ $appointment->notes }}</textarea>
                </div>

            </div>

            <div class="d-flex gap-2 mt-4">

                <button class="btn btn-primary px-4">
                    <i class="fas fa-save me-1"></i>
                    {{ __('app.update') }}
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
        font-size: 0.9rem;
        padding: 9px;
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

    .card {
        border-radius: 14px;
    }
</style>