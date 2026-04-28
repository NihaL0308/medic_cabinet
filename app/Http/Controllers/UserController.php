<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $patients = User::query()
            ->where('role', 'patient')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        $medecins = User::query()
            ->where('role', 'medecin')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('specialite', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'patients' => view('users.partials.patients-rows', compact('patients'))->render(),
                'medecins' => view('users.partials.medecins-rows', compact('medecins'))->render(),
                'patients_count' => $patients->count(),
                'medecins_count' => $medecins->count(),
            ]);
        }

        return view('users.index', compact('patients', 'medecins'));
    }

    public function store(Request $request)
    {
        User::create($this->validateUser($request));

        return redirect()
            ->route('users.index')
            ->with('success', __('app.user_created'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $user->update($this->validateUser($request, $user));

        return redirect()
            ->route('users.index')
            ->with('success', __('app.user_updated'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', __('app.cannot_delete_self'));
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', __('app.user_deleted'));
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user?->id)],
            'role' => ['required', Rule::in(['patient', 'medecin'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialite' => ['nullable', 'string', 'max:255'],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', 'min:8'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if (($data['role'] ?? null) !== 'medecin') {
            $data['specialite'] = null;
        }

        return $data;
    }
}
