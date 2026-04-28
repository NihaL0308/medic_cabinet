<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $services = Service::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('services.index', compact('services', 'search'));
    }

    public function store(Request $request)
    {
        Service::create($this->validateService($request));

        return redirect()
            ->route('services.index')
            ->with('success', 'Service cree avec succes.');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->validateService($request));

        return redirect()
            ->route('services.index')
            ->with('success', 'Service mis a jour.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Service supprime.');
    }

    private function validateService(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duree_minutes' => ['required', 'integer', 'min:5'],
            'prix' => ['nullable', 'numeric', 'min:0'],
        ]);
    }
}
