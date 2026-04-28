<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AppointmentApiController extends Controller
{
    public function index(Request $request)
    {
        $appointments = $this->queryAppointments($request)
            ->latest('appointment_date')
            ->latest('appointment_time')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $appointments->count(),
            'data' => $appointments->map(fn (Appointment $appointment) => $this->format($appointment)),
        ]);
    }

    public function show(int $id)
    {
        $appointment = Appointment::with(['patient', 'medecin', 'service'])->findOrFail($id);
        $this->authorizeAppointment($appointment, request());

        return response()->json([
            'success' => true,
            'data' => $this->format($appointment),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:users,id'],
            'medecin_id' => ['required', 'exists:users,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        if ($user->isPatient()) {
            $data['patient_id'] = $user->id;
        }

        abort_if($user->isMedecin(), 403);

        $data['statut'] = 'en_attente';

        $appointment = Appointment::create($data);
        $appointment->load(['patient', 'medecin', 'service']);

        return response()->json([
            'success' => true,
            'message' => 'Rendez-vous cree avec succes.',
            'data' => $this->format($appointment),
        ], 201);
    }

    private function format(Appointment $appointment): array
    {
        return [
            'id' => $appointment->id,
            'patient' => [
                'id' => $appointment->patient?->id,
                'name' => $appointment->patient?->name,
                'email' => $appointment->patient?->email,
            ],
            'medecin' => [
                'id' => $appointment->medecin?->id,
                'name' => $appointment->medecin?->name,
                'specialite' => $appointment->medecin?->specialite,
            ],
            'service' => [
                'id' => $appointment->service?->id,
                'name' => $appointment->service?->name,
                'prix' => $appointment->service?->prix,
            ],
            'appointment_date' => optional($appointment->appointment_date)->format('Y-m-d'),
            'appointment_time' => $appointment->appointment_time,
            'statut' => $appointment->statut,
            'notes' => $appointment->notes,
            'created_at' => $appointment->created_at?->toISOString(),
        ];
    }

    private function queryAppointments(Request $request): Builder
    {
        $user = $request->user();

        $query = Appointment::query()->with(['patient', 'medecin', 'service']);

        if ($user->isPatient()) {
            $query->where('patient_id', $user->id);
        } elseif ($user->isMedecin()) {
            $query->where('medecin_id', $user->id);
        }

        return $query;
    }

    private function authorizeAppointment(Appointment $appointment, Request $request): void
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return;
        }

        if ($user->isPatient() && $appointment->patient_id === $user->id) {
            return;
        }

        if ($user->isMedecin() && $appointment->medecin_id === $user->id) {
            return;
        }

        abort(403);
    }
}
