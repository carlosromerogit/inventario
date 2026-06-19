<?php

namespace App\Http\Controllers;

use App\Models\BrandModel;
use App\Models\Computer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OperatingSystem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ComputerController extends Controller
{
    //
    public function index(): View
    {
        $computers = Computer::with(['brandModel.brand', 'department', 'employee', 'operatingSystem'])
            ->orderBy('serial')
            ->paginate(15);
 
        return view('computers.index', compact('computers'));
    }
 
    public function create(): View
    {
        [$brandModels, $departments, $employees, $operatingSystems] = $this->formData();
 
        return view('computers.create', compact('brandModels', 'departments', 'employees', 'operatingSystems'));
    }
 
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);
 
        $computer = Computer::create($validated);
 
        return redirect()->route('computers.show', $computer)->with('success', 'Computadora registrada correctamente.');
    }
 
    public function show(Computer $computer): View
    {
        $computer->load([
            'brandModel.brand',
            'department',
            'employee',
            'operatingSystem',
            'drives.driveType',
            'drives.brandModel.brand',
            'drives.capacity',
            'images',
        ]);
 
        return view('computers.show', compact('computer'));
    }
 
    public function edit(Computer $computer): View
    {
        [$brandModels, $departments, $employees, $operatingSystems] = $this->formData();
 
        return view('computers.edit', compact('computer', 'brandModels', 'departments', 'employees', 'operatingSystems'));
    }
 
    public function update(Request $request, Computer $computer): RedirectResponse
    {
        $validated = $this->validateData($request, $computer->id);
 
        $computer->update($validated);
 
        return redirect()->route('computers.show', $computer)->with('success', 'Computadora actualizada correctamente.');
    }
 
    public function destroy(Computer $computer): RedirectResponse
    {
        $computer->delete();
 
        return redirect()->route('computers.index')->with('success', 'Computadora eliminada correctamente.');
    }
 
    private function validateData(Request $request, ?int $computerId = null): array
    {
        return $request->validate([
            'brand_model_id' => ['required', 'exists:brand_models,id'],
            'serial' => [
                'required',
                'string',
                'max:255',
                'unique:computers,serial' . ($computerId ? ',' . $computerId : ''),
            ],
            'department_id' => ['nullable', 'exists:departments,id'],
            'processor' => ['nullable', 'string', 'max:255'],
            'ram' => ['nullable', 'string', 'max:255'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'operating_system_id' => ['nullable', 'exists:operating_systems,id'],
        ]);
    }
 
    /**
     * Datos comunes para los formularios create/edit.
     */
    private function formData(): array
    {
        return [
            BrandModel::with('brand')->orderBy('name')->get(),
            Department::orderBy('name')->get(),
            Employee::orderBy('last_name')->get(),
            OperatingSystem::orderBy('name')->get(),
        ];
    }

}
