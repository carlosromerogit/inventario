<?php

namespace App\Http\Controllers;

use App\Models\DriveType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;


class DriveTypeController extends Controller implements HasMiddleware
{
    //
      public static function middleware(): array
    {
        return [
            new Middleware('permission:drive_types.index', only: ['index']),
            new Middleware('permission:drive_types.create', only: ['create']),
            new Middleware('permission:drive_types.store', only: ['store']),
            new Middleware('permission:drive_types.show', only: ['show']),
            new Middleware('permission:drive_types.edit', only: ['edit']),
            new Middleware('permission:drive_types.update', only: ['update']),
            new Middleware('permission:drive_types.destroy', only: ['destroy']),
        ];
    }

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
        ], [], [
            'name' => 'nombre',
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
        ], [], [
            'name' => 'nombre',
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
