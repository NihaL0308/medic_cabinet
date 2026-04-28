<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    private function adminOnly() {
        if (!auth()->user()->isAdmin()) abort(403);
    }

    public function index(Request $request) {
        $this->adminOnly();

        $q = $request->input('search', '');

        $patients = User::where('role', 'patient')
            ->when($q, fn($query) => $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                      ->orWhere('email', 'like', "%$q%");
            }))->get();

        $medecins = User::where('role', 'medecin')
            ->when($q, fn($query) => $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                      ->orWhere('email', 'like', "%$q%");
            }))->get();

        if ($request->ajax()) {
            return response()->json([
                'patients'       => view('users.partials.patients-rows', compact('patients'))->render(),
                'medecins'       => view('users.partials.medecins-rows',  compact('medecins'))->render(),
                'patients_count' => $patients->count(),
                'medecins_count' => $medecins->count(),
            ]);
        }

        return view('users.index', compact('patients', 'medecins'));
    }

    public function create() {
        $this->adminOnly();
        return view('users.create');
    }

    public function store(Request $request) {
        $this->adminOnly();
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:8|confirmed',
            'role'       => 'required|in:patient,medecin',
            'phone'      => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:255',
        ]);

        User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => $data['role'],
            'phone'      => $data['phone'] ?? null,
            'specialite' => $data['specialite'] ?? null,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user) {
        $this->adminOnly();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $this->adminOnly();
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,'.$user->id,
            'role'       => 'required|in:patient,medecin',
            'phone'      => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:255',
            'password'   => 'nullable|min:8|confirmed',
        ]);

        $user->update([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'role'       => $data['role'],
            'phone'      => $data['phone'] ?? null,
            'specialite' => $data['specialite'] ?? null,
            'password'   => $data['password'] ? Hash::make($data['password']) : $user->password,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user) {
        $this->adminOnly();
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé.');
    }

    public function show(User $user) {
        $this->adminOnly();
        return view('users.show', compact('user'));
    }
}