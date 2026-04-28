<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use App\Mail\AppointmentConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller {

    public function index(Request $request) {
    $query = Appointment::with(['patient', 'medecin', 'service']);

    if (auth()->user()->isPatient()) {
        $query->where('patient_id', auth()->id());
    } elseif (auth()->user()->isMedecin()) {
        $query->where('medecin_id', auth()->id());
    }

    if ($search = $request->get('search')) {
        $query->where(function($q) use ($search) {
            $q->whereHas('patient',  fn($q) => $q->where('name', 'like', "%$search%"))
              ->orWhereHas('medecin', fn($q) => $q->where('name', 'like', "%$search%"))
              ->orWhereHas('service', fn($q) => $q->where('name', 'like', "%$search%"))
              ->orWhere('statut', 'like', "%$search%");
        });
    }

    $appointments = $query->latest()->paginate(10);

    if ($request->ajax()) {
        return view('appointments.partials.table', compact('appointments'))->render();
    }

    // Données pour le modal
    $medecins = \App\Models\User::where('role', 'medecin')->get();
    $services = \App\Models\Service::all();
    $patients = \App\Models\User::where('role', 'patient')->get();

    return view('appointments.index', compact('appointments', 'medecins', 'services', 'patients'));
}

    public function create() {
        $medecins = User::where('role', 'medecin')->get();
        $services = Service::all();
        $patients = User::where('role', 'patient')->get();
        return view('appointments.create', compact('medecins', 'services', 'patients'));
    }

public function store(Request $request) {
    $data = $request->validate([
        'patient_id'       => 'sometimes|exists:users,id',
        'medecin_id'       => 'required|exists:users,id',
        'service_id'       => 'required|exists:services,id',
        'appointment_date' => 'required|date|after_or_equal:today',
        'appointment_time' => 'required',
        'notes'            => 'nullable|string|max:500',
    ]);

    if (auth()->user()->isPatient()) {
        $data['patient_id'] = auth()->id();
    }

    $appointment = Appointment::create($data);

    return redirect()->route('appointments.index')
        ->with('success', __('app.appointment_created'));
}

    public function show(Appointment $appointment) {
        $this->authorizeAppointment($appointment);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment) {
        $this->authorizeAppointment($appointment);
        $medecins = User::where('role', 'medecin')->get();
        $services = Service::all();
        $patients = User::where('role', 'patient')->get();
        return view('appointments.edit', compact('appointment', 'medecins', 'services', 'patients'));
    }

    public function update(Request $request, Appointment $appointment) {
    $this->authorizeAppointment($appointment);
    
    $ancienStatut = $appointment->statut; // ← sauvegarder l'ancien statut
    
    $data = $request->validate([
        'medecin_id'       => 'required|exists:users,id',
        'service_id'       => 'required|exists:services,id',
        'appointment_date' => 'required|date',
        'appointment_time' => 'required',
        'statut'           => 'required|in:en_attente,confirme,annule,termine',
        'notes'            => 'nullable|string|max:500',
    ]);

    $appointment->update($data);

    // ← Envoyer email seulement quand statut passe à "confirme"
    if ($ancienStatut !== 'confirme' && $data['statut'] === 'confirme') {
        try {
            Mail::to($appointment->patient->email)
                ->send(new AppointmentConfirmation($appointment));
        } catch (\Exception $e) {}
    }

    return redirect()->route('appointments.index')
        ->with('success', __('app.appointment_updated'));
}

    public function destroy(Appointment $appointment) {
        $this->authorizeAppointment($appointment);
        $appointment->delete();
        return redirect()->route('appointments.index')
            ->with('success', __('app.appointment_deleted'));
    }

    private function authorizeAppointment(Appointment $a) {
        if (auth()->user()->isPatient() && $a->patient_id !== auth()->id()) abort(403);
        if (auth()->user()->isMedecin() && $a->medecin_id !== auth()->id()) abort(403);
    }
}