<?php
namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller {

    public function index() {
        $services = Service::paginate(10);
        return view('services.index', compact('services'));
    }

    public function create() {
        return view('services.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'duree_minutes' => 'required|integer|min:5',
            'prix'          => 'nullable|numeric|min:0',
        ]);
        Service::create($data);
        return redirect()->route('services.index')->with('success', 'Service créé avec succès.');
    }

    public function edit(Service $service) {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service) {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'duree_minutes' => 'required|integer|min:5',
            'prix'          => 'nullable|numeric|min:0',
        ]);
        $service->update($data);
        return redirect()->route('services.index')->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service) {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service supprimé.');
    }

    public function show(Service $service) {
        return view('services.show', compact('service'));
    }
}