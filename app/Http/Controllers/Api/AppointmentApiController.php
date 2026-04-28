<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentApiController extends Controller {

    public function index() {
        $appointments = Appointment::with(['patient', 'medecin', 'service'])->latest()->get();
        return response()->json([
            'success' => true,
            'count'   => $appointments->count(),
            'data'    => $appointments->map(fn($a) => $this->format($a)),
        ]);
    }

    public function show($id) {
        $a = Appointment::with(['patient', 'medecin', 'service'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $this->format($a)]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'patient_id'       => 'required|exists:users,id',
            'medecin_id'       => 'required|exists:users,id',
            'service_id'       => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes'            => 'nullable|string',
        ]);

        $appointment = Appointment::create($data);
        $appointment->load(['patient', 'medecin', 'service']);

        return response()->json([
            'success' => true,
            'message' => 'Rendez-vous créé avec succès.',
            'data'    => $this->format($appointment),
        ], 201);
    }

    private function format(Appointment $a): array {
        return [
            'id'               => $a->id,
            'patient'          => ['id' => $a->patient->id, 'name' => $a->patient->name, 'email' => $a->patient->email],
            'medecin'          => ['id' => $a->medecin->id, 'name' => $a->medecin->name, 'specialite' => $a->medecin->specialite],
            'service'          => ['id' => $a->service->id, 'name' => $a->service->name, 'prix' => $a->service->prix],
            'appointment_date' => $a->appointment_date->format('Y-m-d'),
            'appointment_time' => $a->appointment_time,
            'statut'           => $a->statut,
            'notes'            => $a->notes,
            'created_at'       => $a->created_at->toISOString(),
        ];
    }
}