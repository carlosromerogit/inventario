<?php

namespace App\Http\Controllers;

use App\Models\DriveType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class DriveTypeController extends Controller
{
    //
    public function index(): View
    {
        $driveTypes = DriveType::orderBy('name')->paginate(15);
 
        return view('drive-types.index', compact('driveTypes'));
    }
 
    public function create(): View
    {
        return view('drive-types.create');
    }
 
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:drive_types,name'],
        ]);
 
        DriveType::create($validated);
 
        return redirect()->route('drive-types.index')->with('success', 'Tipo de disco creado correctamente.');
    }
 
    public function show(DriveType $driveType): View
    {
        return view('drive-types.show', compact('driveType'));
    }
 
    public function edit(DriveType $driveType): View
    {
        return view('drive-types.edit', compact('driveType'));
    }
 
    public function update(Request $request, DriveType $driveType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:drive_types,name,' . $driveType->id],
        ]);
 
        $driveType->update($validated);
 
        return redirect()->route('drive-types.index')->with('success', 'Tipo de disco actualizado correctamente.');
    }
 
    public function destroy(DriveType $driveType): RedirectResponse
    {
        $driveType->delete();
 
        return redirect()->route('drive-types.index')->with('success', 'Tipo de disco eliminado correctamente.');
    }

}
