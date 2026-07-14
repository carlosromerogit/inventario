<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use App\Models\Warranty;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class WarrantyController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the warranties with their associated equipment.
     */

       public static function middleware(): array
    {
        return [
            new Middleware('permission:warranties.index', only: ['index']),
            new Middleware('permission:warranties.create', only: ['create']),
            new Middleware('permission:warranties.store', only: ['store']),
            new Middleware('permission:warranties.show', only: ['show']),
            new Middleware('permission:warranties.edit', only: ['edit']),
            new Middleware('permission:warranties.update', only: ['update']),
            new Middleware('permission:warranties.destroy', only: ['destroy']),
        ];
    }




public function index()
{
    $warranties = Warranty::with('computers')
        ->latest()
        ->paginate(10);

    return view('warranties.index', compact('warranties'));
}
    /**
     * Show the form for creating a new warranty.
     */
    public function create()
    {
        // Map of available equipment types for the dropdown
        $equipmentTypes = [
            'App\Models\Computer' => 'Computer',
            'App\Models\Monitor'  => 'Monitor',
            'App\Models\Router'   => 'Router',
        ];

        return view('warranties.create', compact('equipmentTypes'));
    }

    /**
     * Store a newly created warranty in storage.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'warranty_code'    => 'required|string|max:255|unique:warranties,warranty_code',
        'provider'         => 'required|string|max:255',
        'start_date'       => 'required|date',
        'end_date'         => 'required|date|after_or_equal:start_date',
        'warrantable_type' => 'required|string',
        'warrantable_ids'  => 'required|array|min:1',
        'warrantable_ids.*'=> 'integer',
        'notes'            => 'nullable|string',
        'document' => 'nullable|file|mimes:pdf|max:10240',
    ], [], [
        'warranty_code'     => 'código de garantía',
        'provider'          => 'proveedor',
        'start_date'        => 'fecha de inicio',
        'end_date'          => 'fecha de finalización',
        'warrantable_type'  => 'tipo de equipo',
        'warrantable_ids'   => 'equipos asociados',
        'warrantable_ids.*' => 'ID de equipo',
        'notes'             => 'notas',
        'document'          => 'documento de garantía',
    ]);

    $documentPath = null;

    if ($request->hasFile('document')) {

        $documentPath = $request->file('document')
            ->store('warranties', 'public');

    }

   $hasActiveWarranty = Computer::whereIn('id', $validated['warrantable_ids'])
    ->whereHas('warranties', function ($query) {
        $query->whereDate('end_date', '>=', now());
    })
    ->exists();

if ($hasActiveWarranty) {
    return back()
        ->withInput()
        ->withErrors([
            'warrantable_ids' => 'Uno o más equipos ya tienen una garantía activa.'
        ]);
}
    $warranty = Warranty::create([
        'warranty_code' => $validated['warranty_code'],
        'provider'      => $validated['provider'],
        'start_date'    => $validated['start_date'],
        'end_date'      => $validated['end_date'],
        'notes'         => $validated['notes'],
        'document_path' => $documentPath,
    ]);

    switch ($validated['warrantable_type']) {

        case Computer::class:
            $warranty->computers()->sync($validated['warrantable_ids']);
            break;

        // case Monitor::class:
        //     $warranty->monitors()->sync($validated['warrantable_ids']);
        //     break;

        // case Router::class:
        //     $warranty->routers()->sync($validated['warrantable_ids']);
        //     break;

        default:
            return back()->withErrors([
                'warrantable_type' => 'Tipo de equipo no válido.'
            ]);
    }

    return redirect()
        ->route('warranties.index')
        ->with('success', 'Garantía creada correctamente.');
}
    /**
     * Display the specified warranty.
     */
public function show(Warranty $warranty)
{
    $warranty->load('computers');

    return view('warranties.show', compact('warranty'));
}
    /**
     * Show the form for editing the specified warranty.
     */
    public function edit(Warranty $warranty)
    {
        $equipmentTypes = [
            'App\Models\Computer' => 'Computer',
            'App\Models\Monitor'  => 'Monitor',
            'App\Models\Router'   => 'Router',
        ];

        // Fetch all assets of the current type so the edit dropdown can pre-select the active one
        $currentTypeAssets = $warranty->warrantable_type::all();

        return view('warranties.edit', compact('warranty', 'equipmentTypes', 'currentTypeAssets'));
    }

    /**
     * Update the specified warranty in storage.
     */
    public function update(Request $request, Warranty $warranty)
    {
        $validated = $request->validate([
            'warranty_code'    => ['required', 'string', Rule::unique('warranties')->ignore($warranty->id)],
            'provider'         => 'required|string|max:255',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'warrantable_type' => ['required', 'string', Rule::in(['App\Models\Computer', 'App\Models\Monitor', 'App\Models\Router'])],
            'warrantable_id'   => 'required|integer',
            'notes'            => 'nullable|string',
        ], [], [
             'warranty_code'    => 'código de garantía',
            'provider'         => 'proveedor',
            'start_date'       => 'fecha de inicio',
            'end_date'         => 'fecha de finalización',
            'warrantable_type' => 'tipo de equipo',
            'warrantable_id'   => 'equipo',
            'notes'            => 'notas',
        ]);

        $modelClass = $validated['warrantable_type'];
        if (!$modelClass::where('id', $validated['warrantable_id'])->exists()) {
            return back()
                ->withInput()
                ->withErrors(['warrantable_id' => 'The selected equipment asset does not exist.']);
        }

        $warranty->update($validated);

        return redirect()
            ->route('warranties.index')
            ->with('success', 'Warranty updated successfully.');
    }

    /**
     * Remove the specified warranty from storage.
     */
    public function destroy(Warranty $warranty)
    {
        $warranty->delete();

        return redirect()
            ->route('warranties.index')
            ->with('success', 'Warranty record deleted successfully.');
    }

    /**
     * API Endpoint: Fetch devices dynamically based on the selected polymorphic type.
     * Accessible via an AJAX call from your frontend view.
     */
public function getDevicesByType(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', Rule::in(['App\Models\Computer', 'App\Models\Monitor', 'App\Models\Router'])]
        ]);

        $modelClass = $request->query('type');
        
        // 1. Fetch data adaptively based on the model's actual schema attributes
        if ($modelClass === 'App\Models\Computer') {
            // Safe fallback query matching your real Computer table schema attributes
            $rawDevices = $modelClass::select('id', 'serial', 'hostname')->get();
            
            // Map the data into standard frontend visual keys: label and dynamic_serial
            $devices = $rawDevices->map(function ($device) {
                return [
                    'id'             => $device->id,
                    'label'          => $device->hostname ?? 'Computer Asset',
                    'dynamic_serial' => $device->serial ?? 'No S/N'
                ];
            });
        } else {
            // Fallback strategy for Monitors and Routers (Adjust column names here if they differ)
            $rawDevices = $modelClass::select('id', 'serial', 'brand_model_id')->get();
            
            $devices = $rawDevices->map(function ($device) {
                return [
                    'id'             => $device->id,
                    'label'          => 'Device Asset',
                    'dynamic_serial' => $device->serial ?? 'No S/N'
                ];
            });
        }

        return response()->json($devices);
    }
}
