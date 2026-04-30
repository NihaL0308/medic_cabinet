<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = $this->appointmentsQuery($request)
            ->latest('appointment_date')
            ->latest('appointment_time')
            ->get();

        if ($request->ajax()) {
            return view('appointments.partials.table', compact('appointments'))->render();
        }

        return view('appointments.partials.index', [
            'appointments' => $appointments,
            'patients' => User::where('role', 'patient')->orderBy('name')->get(),
            'medecins' => User::where('role', 'medecin')->orderBy('name')->get(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('appointments.partials.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validateAppointment($request, true);

        Appointment::create($data);

        return redirect()
            ->route('appointments.index')
            ->with('success', __('app.appointment_created'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->loadMissing(['patient', 'medecin', 'service']);
        $this->authorizeView($appointment);

        return view('appointments.partials.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $appointment->loadMissing(['patient', 'medecin', 'service']);
        $this->authorizeView($appointment);

        return view('appointments.partials.edit', array_merge(
            ['appointment' => $appointment],
            $this->formData()
        ));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $appointment->loadMissing(['patient', 'medecin', 'service']);
        $this->authorizeUpdate($appointment);

        $data = $this->validateAppointment($request, false, $appointment);
        $appointment->update($data);

        return redirect()
            ->route('appointments.index')
            ->with('success', __('app.appointment_updated'));
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorizeCancel($appointment);
        $appointment->update(['statut' => 'annule']);

        return redirect()
            ->route('appointments.index')
            ->with('success', __('app.appointment_deleted'));
    }

    private function appointmentsQuery(Request $request): Builder
    {
        $user = $request->user();
        $search = trim((string) $request->input('search', ''));

        $query = Appointment::query()->with(['patient', 'medecin', 'service']);

        if ($user->isPatient()) {
            $query->where('patient_id', $user->id);
        } elseif ($user->isMedecin()) {
            $query->where('medecin_id', $user->id);
        }

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search) {
                $builder->whereHas('patient', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('medecin', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('service', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
                    ->orWhere('statut', 'like', "%{$search}%")
                    ->orWhere('appointment_date', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    private function formData(): array
    {
        $user = auth()->user();

        return [
            'patients' => User::where('role', 'patient')->orderBy('name')->get(),
            'medecins' => User::where('role', 'medecin')->orderBy('name')->get(),
            'services' => Service::query()
                ->with('medecin:id,name')
                ->when($user?->isMedecin(), fn (Builder $query) => $query->where('medecin_id', $user->id))
                ->orderBy('name')
                ->get(),
        ];
    }

    private function validateAppointment(Request $request, bool $isCreate, ?Appointment $appointment = null): array
    {
        $user = $request->user();

        $rules = [
            'patient_id' => ['required', 'exists:users,id'],
            'medecin_id' => ['required', 'exists:users,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];

        if (! $isCreate) {
            $rules['statut'] = ['required', 'in:en_attente,confirme,annule,termine'];
        }

        $data = $request->validate($rules);

        if ($user->isPatient()) {
            $data['patient_id'] = $user->id;
        }

        if ($user->isMedecin()) {
            $data['medecin_id'] = $appointment?->medecin_id ?? $data['medecin_id'];
            $data['patient_id'] = $appointment?->patient_id ?? $data['patient_id'];
            $data['service_id'] = $appointment?->service_id ?? $data['service_id'];
            $data['appointment_date'] = $appointment?->appointment_date?->format('Y-m-d') ?? $data['appointment_date'];
            $data['appointment_time'] = $appointment?->appointment_time ?? $data['appointment_time'];
            $data['notes'] = $appointment?->notes ?? $data['notes'];
        }

        if ($isCreate) {
            $data['statut'] = 'en_attente';
        } elseif ($user->isPatient()) {
            $data['medecin_id'] = $appointment->medecin_id;
            $data['service_id'] = $appointment->service_id;
            $data['appointment_date'] = $appointment->appointment_date->format('Y-m-d');
            $data['appointment_time'] = $appointment->appointment_time;
            $data['notes'] = $appointment->notes;
            $data['statut'] = 'annule';
        } elseif ($user->isMedecin()) {
            $allowedStatuses = ['confirme', 'termine', 'annule'];
            abort_unless(in_array($data['statut'], $allowedStatuses, true), 422);
        }

        $service = Service::query()->findOrFail($data['service_id']);

        if ($service->medecin_id !== null && (int) $service->medecin_id !== (int) $data['medecin_id']) {
            throw ValidationException::withMessages([
                'service_id' => __('validation.exists', ['attribute' => 'service']),
            ]);
        }

        return $data;
    }

    private function authorizeView(Appointment $appointment): void
    {
        $user = auth()->user();

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

    private function authorizeUpdate(Appointment $appointment): void
    {
        $user = auth()->user();

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

    private function authorizeCancel(Appointment $appointment): void
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return;
        }

        if ($user->isPatient() && $appointment->patient_id === $user->id) {
            return;
        }

        abort(403);
    }
}
