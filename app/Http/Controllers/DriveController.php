<?php

namespace App\Http\Controllers;

use App\Models\Capacity;
use App\Models\Computer;
use App\Models\Drive;
use App\Models\DriveType;
use App\Models\BrandModel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DriveController extends Controller
{
    //
    public function create(Computer $computer): View
    {
        [$driveTypes, $brandModels, $capacities] = $this->formData();
 
        return view('drives.create', compact('computer', 'driveTypes', 'brandModels', 'capacities'));
    }
 
    public function store(Request $request, Computer $computer): RedirectResponse
    {
        $validated = $request->validate([
            'drive_type_id' => ['required', 'exists:drive_types,id'],
            'brand_model_id' => ['required', 'exists:brand_models,id'],
            'capacity_id' => ['required', 'exists:capacities,id'],
        ]);
 
        $validated['computer_id'] = $computer->id;
 
        Drive::create($validated);
 
        return redirect()->route('computers.show', $computer)->with('success', 'Disco agregado correctamente.');
    }
 
    public function edit(Computer $computer, Drive $drive): View
    {
        [$driveTypes, $brandModels, $capacities] = $this->formData();
 
        return view('drives.edit', compact('computer', 'drive', 'driveTypes', 'brandModels', 'capacities'));
    }
 
    public function update(Request $request, Computer $computer, Drive $drive): RedirectResponse
    {
        $validated = $request->validate([
            'drive_type_id' => ['required', 'exists:drive_types,id'],
            'brand_model_id' => ['required', 'exists:brand_models,id'],
            'capacity_id' => ['required', 'exists:capacities,id'],
        ]);
 
        $drive->update($validated);
 
        return redirect()->route('computers.show', $computer)->with('success', 'Disco actualizado correctamente.');
    }
 
    public function destroy(Computer $computer, Drive $drive): RedirectResponse
    {
        $drive->delete();
 
        return redirect()->route('computers.show', $computer)->with('success', 'Disco eliminado correctamente.');
    }
 
    private function formData(): array
    {
        return [
            DriveType::orderBy('name')->get(),
            BrandModel::with('brand')->orderBy('name')->get(),
            Capacity::orderBy('name')->get(),
        ];
    }

}
