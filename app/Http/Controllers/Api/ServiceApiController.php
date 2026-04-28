<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceApiController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $services->count(),
            'data' => $services->map(fn (Service $service) => $this->format($service)),
        ]);
    }

    public function show(Service $service)
    {
        return response()->json([
            'success' => true,
            'data' => $this->format($service),
        ]);
    }

    public function store(Request $request)
    {
        $service = Service::create($this->validateService($request));

        return response()->json([
            'success' => true,
            'message' => 'Service cree avec succes.',
            'data' => $this->format($service),
        ], 201);
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->validateService($request));

        return response()->json([
            'success' => true,
            'message' => 'Service mis a jour.',
            'data' => $this->format($service->fresh()),
        ]);
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service supprime.',
        ]);
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

    private function format(Service $service): array
    {
        return [
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'duree_minutes' => $service->duree_minutes,
            'prix' => $service->prix,
            'created_at' => $service->created_at?->toISOString(),
        ];
    }
}
