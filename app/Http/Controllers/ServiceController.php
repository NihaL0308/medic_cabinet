<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Service::class);

        $search = trim((string) $request->input('search', ''));
        $user = $request->user();

        $services = Service::query()
            ->with('medecin:id,name,specialite')
            ->when($user->isMedecin(), fn (Builder $query) => $query->where('medecin_id', $user->id))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('medecin', fn (Builder $medecins) => $medecins->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('services.index', [
            'services' => $services,
            'search' => $search,
            'medecins' => $this->availableMedecins($user),
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Service::class);

        return view('services.create', [
            'service' => new Service(),
            'medecins' => $this->availableMedecins($request->user()),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Service::class);

        Service::create($this->validateService($request));

        return redirect()
            ->route('services.index')
            ->with('success', __('app.service_created'));
    }

    public function edit(Request $request, Service $service)
    {
        $this->authorize('update', $service);

        return view('services.edit', [
            'service' => $service,
            'medecins' => $this->availableMedecins($request->user()),
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $this->authorize('update', $service);

        $service->update($this->validateService($request));

        return redirect()
            ->route('services.index')
            ->with('success', __('app.service_updated'));
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);

        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', __('app.service_deleted'));
    }

    private function validateService(Request $request): array
    {
        $user = $request->user();

        $data = $request->validate([
            'medecin_id' => [
                Rule::requiredIf($user->isAdmin()),
                'nullable',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'medecin')),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'duree_minutes' => ['required', 'integer', 'min:5'],
            'prix' => ['nullable', 'numeric', 'min:0'],
        ]);

        if ($user->isMedecin()) {
            $data['medecin_id'] = $user->id;
        }

        return $data;
    }

    private function availableMedecins(User $user)
    {
        if ($user->isMedecin()) {
            return User::query()
                ->whereKey($user->id)
                ->get(['id', 'name', 'specialite']);
        }

        return User::query()
            ->where('role', 'medecin')
            ->orderBy('name')
            ->get(['id', 'name', 'specialite']);
    }
}
