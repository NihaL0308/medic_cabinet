
@extends('layouts.app')
@section('title', __('app.appointment_detail'))
@section('page-title', __('app.appointment_detail'))

@section('content')
<div class="card border-0 shadow-sm" style="max-width:700px;margin:auto">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">
                {{ __('app.appointment_detail') }} 
            </h5>

            <span class="badge px-3 py-2 fs-6
                @if($appointment->statut=='confirme')
                @elseif($appointment->statut=='en_attente') 
                @elseif($appointment->statut=='annule') 
                @else 
                @endif">
                {{ __('app.statut_'.$appointment->statut) }}
            </span>
        </div>

        <div class="row g-3">

            <div class="col-md-6">
                <div class="card bg-light border-0 p-3 h-100">
                    <p class="text-muted small mb-1">
                        {{ __('app.patient') }}
                    </p>
                    <p class="fw-semibold mb-0">{{ $appointment->patient->name }}</p>
                    <p class="text-muted small mb-0">{{ $appointment->patient->email }}</p>
                    <p class="text-muted small mb-0">{{ $appointment->patient->phone }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card bg-light border-0 p-3 h-100">
                    <p class="text-muted small mb-1">
                        {{ __('app.doctor') }}
                    </p>
                    <p class="fw-semibold mb-0">{{ $appointment->medecin->name }}</p>
                    <p class="text-muted small mb-0">{{ $appointment->medecin->specialite }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card bg-light border-0 p-3">
                    <p class="text-muted small mb-1">
                       {{ __('app.service') }}
                    </p>
                    <p class="fw-semibold mb-0">{{ $appointment->service->name }}</p>
                    <p class="text-muted small mb-0">
                        {{ $appointment->service->duree_minutes }} {{ __('app.minutes') }}
                        @if($appointment->service->prix)
                            — {{ $appointment->service->prix }} MAD
                        @endif
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card bg-light border-0 p-3">
                    <p class="text-muted small mb-1">
                        {{ __('app.date_time') }}
                    </p>
                    <p class="fw-semibold mb-0">{{ $appointment->appointment_date->format('d/m/Y') }}</p>
                    <p class="text-muted small mb-0">{{ $appointment->appointment_time }}</p>
                </div>
            </div>

            @if($appointment->notes)
            <div class="col-12">
                <div class="card bg-light border-0 p-3">
                    <p class="text-muted small mb-1">
                        {{ __('app.notes') }}
                    </p>
                    <p class="mb-0">{{ $appointment->notes }}</p>
                </div>
            </div>
            @endif

        </div>

        <div class="d-flex gap-2 mt-4">

            <a href="{{ route('appointments.edit', $appointment) }}" >
                {{ __('app.edit') }}
            </a>

            <a href="{{ route('appointments.index') }}">
                {{ __('app.back') }}
            </a>

        </div>
    </div>
</div>
@endsection