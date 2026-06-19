<?php

namespace App\Http\Controllers;

 
use App\Models\OperatingSystem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OperatingSystemController extends Controller
{
    //
    public function index(): View
    {
        $operatingSystems = OperatingSystem::orderBy('name')->paginate(15);
 
        return view('operating-systems.index', compact('operatingSystems'));
    }
 
    public function create(): View
    {
        return view('operating-systems.create');
    }
 
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:operating_systems,name'],
        ]);
 
        OperatingSystem::create($validated);
 
        return redirect()->route('operating-systems.index')->with('success', 'Sistema operativo creado correctamente.');
    }
 
    public function show(OperatingSystem $operatingSystem): View
    {
        return view('operating-systems.show', compact('operatingSystem'));
    }
 
    public function edit(OperatingSystem $operatingSystem): View
    {
        return view('operating-systems.edit', compact('operatingSystem'));
    }
 
    public function update(Request $request, OperatingSystem $operatingSystem): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:operating_systems,name,' . $operatingSystem->id],
        ]);
 
        $operatingSystem->update($validated);
 
        return redirect()->route('operating-systems.index')->with('success', 'Sistema operativo actualizado correctamente.');
    }
 
    public function destroy(OperatingSystem $operatingSystem): RedirectResponse
    {
        $operatingSystem->delete();
 
        return redirect()->route('operating-systems.index')->with('success', 'Sistema operativo eliminado correctamente.');
    }

}
