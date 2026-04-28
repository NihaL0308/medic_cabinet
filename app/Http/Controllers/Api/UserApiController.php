<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserApiController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->string('role')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $users->count(),
            'data' => $users->map(fn (User $user) => $this->format($user)),
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $this->format($user),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateUser($request);
        $user = User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur cree avec succes.',
            'data' => $this->format($user),
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validateUser($request, $user);
        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur mis a jour.',
            'data' => $this->format($user->fresh()),
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprime.',
        ]);
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user?->id)],
            'role' => ['required', Rule::in(['patient', 'medecin', 'admin'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialite' => ['nullable', 'string', 'max:255'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if (($data['role'] ?? null) !== 'medecin') {
            $data['specialite'] = null;
        }

        return $data;
    }

    private function format(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'specialite' => $user->specialite,
            'created_at' => $user->created_at?->toISOString(),
        ];
    }
}
