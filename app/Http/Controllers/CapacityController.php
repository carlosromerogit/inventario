<?php

namespace App\Http\Controllers;

use App\Models\Capacity;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CapacityController extends Controller
{
    //
    public function index(): View
    {
        $capacities = Capacity::orderBy('name')->paginate(15);
 
        return view('capacities.index', compact('capacities'));
    }
 
    public function create(): View
    {
        return view('capacities.create');
    }
 
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:capacities,name'],
        ]);
 
        Capacity::create($validated);
 
        return redirect()->route('capacities.index')->with('success', 'Capacidad creada correctamente.');
    }
 
    public function show(Capacity $capacity): View
    {
        return view('capacities.show', compact('capacity'));
    }
 
    public function edit(Capacity $capacity): View
    {
        return view('capacities.edit', compact('capacity'));
    }
 
    public function update(Request $request, Capacity $capacity): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:capacities,name,' . $capacity->id],
        ]);
 
        $capacity->update($validated);
 
        return redirect()->route('capacities.index')->with('success', 'Capacidad actualizada correctamente.');
    }
 
    public function destroy(Capacity $capacity): RedirectResponse
    {
        $capacity->delete();
 
        return redirect()->route('capacities.index')->with('success', 'Capacidad eliminada correctamente.');
    }

}
