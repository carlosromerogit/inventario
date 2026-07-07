<?php

namespace App\Http\Controllers;

use App\Models\Drive;
use App\Models\Computer;
use App\Models\DriveType;
use App\Models\BrandModel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class DriveController extends Controller implements HasMiddleware
{

  public static function middleware(): array
    {
        return [
            new Middleware('permission:drives.index', only: ['index']),
            new Middleware('permission:drives.create', only: ['create']),
            new Middleware('permission:drives.store', only: ['store']),
            new Middleware('permission:drives.show', only: ['show']),
            new Middleware('permission:drives.edit', only: ['edit']),
            new Middleware('permission:drives.update', only: ['update']),
            new Middleware('permission:drives.destroy', only: ['destroy']),
        ];
    }

    public function create(Computer $computer): View
    {
        $driveTypes = DriveType::orderBy('name')->get();
        
        $brandModels = BrandModel::with('brand')
            ->where('type', 'drive') 
            ->orderBy('name')
            ->get();

        return view('drives.create', compact('computer', 'driveTypes', 'brandModels'));
    }

    public function store(Request $request, Computer $computer): RedirectResponse
    {
        $validated = $request->validate([
            'drive_type_id'  => 'required|exists:drive_types,id',
            'brand_model_id' => 'required|exists:brand_models,id',
            'cap_number'     => 'required|integer|min:1',
            'cap_unit'       => 'required|in:MB,GB,TB',
        ]);

        $computer->drives()->create([
            'drive_type_id'  => $validated['drive_type_id'],
            'brand_model_id' => $validated['brand_model_id'],
            'capacity_value' => $validated['cap_number'],
            'capacity_unit'  => $validated['cap_unit'],
            'capacity_in_mb' => $this->calculateMb($validated['cap_number'], $validated['cap_unit']),
        ]);

        return redirect()->route('computers.show', $computer)
                         ->with('success', 'Disco agregado con éxito.');
    }

    public function edit(Drive $drive): View
    {
        $driveTypes = DriveType::orderBy('name')->get();
        
        $brandModels = BrandModel::with('brand')
            ->where('type', 'drive') 
            ->orderBy('name')
            ->get();

        $computer      = $drive->computer;
        $currentNumber = $drive->capacity_value;
        $currentUnit   = $drive->capacity_unit;

        return view('drives.edit', compact(
            'computer', 'drive', 'driveTypes', 'brandModels', 'currentNumber', 'currentUnit'
        ));
    }

    public function update(Request $request, Drive $drive): RedirectResponse
    {
        $validated = $request->validate([
            'drive_type_id'  => 'required|exists:drive_types,id',
            'brand_model_id' => 'required|exists:brand_models,id',
            'cap_number'     => 'required|integer|min:1',
            'cap_unit'       => 'required|in:MB,GB,TB',
        ]);

        $drive->update([
            'drive_type_id'  => $validated['drive_type_id'],
            'brand_model_id' => $validated['brand_model_id'],
            'capacity_value' => $validated['cap_number'],
            'capacity_unit'  => $validated['cap_unit'],
            'capacity_in_mb' => $this->calculateMb($validated['cap_number'], $validated['cap_unit']),
        ]);

        return redirect()->route('computers.show', $drive->computer_id)
                         ->with('success', 'Disco actualizado con éxito.');
    }

    public function destroy(Drive $drive): RedirectResponse
    {
        $computerId = $drive->computer_id;
        $drive->delete();

        return redirect()->route('computers.show', $computerId)
                         ->with('success', 'Disco removido con éxito.');
    }

    private function calculateMb(int $value, string $unit): int
    {
        return match ($unit) {
            'TB'    => $value * 1024 * 1024,
            'GB'    => $value * 1024,
            default => $value, // MB
        };
    }
}