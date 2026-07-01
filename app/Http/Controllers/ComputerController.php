<?php

namespace App\Http\Controllers;

use App\Models\BrandModel;
use App\Models\Computer;
use App\Models\Department;
use App\Models\Drive;
use App\Models\Brand;
use App\Models\DriveType;
use App\Models\Employee;
use App\Models\Image;
use App\Models\OperatingSystem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ComputerController extends Controller
{
  
public function index(Request $request): View
{
    // Iniciamos la consulta base con sus relaciones cargadas
    $query = Computer::with(['brandModel.brand', 'department', 'employee', 'operatingSystem']);

    // 1. Filtro por Texto: Serial o Nombre del Empleado
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('serial', 'like', "%{$search}%")
              ->orWhereHas('employee', function ($eQ) use ($search) {
                  $eQ->where('first_name', 'like', "%{$search}%")
                     ->orWhere('last_name', 'like', "%{$search}%");
              });
        });
    }

    // 2. Filtro por Marca
    if ($request->filled('brand_id')) {
        $query->whereHas('brandModel', function ($q) use ($request) {
            $q->where('brand_id', $request->input('brand_id'));
        });
    }

    // 3. Filtro por Departamento
    if ($request->filled('department_id')) {
        $query->where('department_id', $request->input('department_id'));
    }

    // 4. Filtro por Sistema Operativo
    if ($request->filled('operating_system_id')) {
        $query->where('operating_system_id', $request->input('operating_system_id'));
    }

    // 5. Filtro por Disponibilidad (Asignado o en Stock)
    if ($request->filled('status')) {
        if ($request->input('status') === 'assigned') {
            $query->whereNotNull('employee_id');
        } elseif ($request->input('status') === 'stock') {
            $query->whereNull('employee_id');
        }
    }

    // Ordenamos y paginamos manteniendo los filtros en los links de paginación
    $computers = $query->orderBy('serial')
                       ->paginate(15)
                       ->withQueryString();

    // Necesitamos cargar los catálogos para llenar los selectores del formulario
    $brands = Brand::orderBy('name')->get();
    $departments = Department::orderBy('name')->get();
    $operatingSystems = OperatingSystem::orderBy('name')->get();

    return view('computers.index', compact('computers', 'brands', 'departments', 'operatingSystems'));
}

    public function create(): View
    {
        // Cargamos los datos compartidos pasándole los modelos correspondientes
        [$brandModels, $departments, $employees, $operatingSystems, $driveTypes, $driveModels] = $this->formData();

        return view('computers.create', compact(
            'brandModels', 'departments', 'employees', 'operatingSystems', 'driveTypes', 'driveModels'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'brand_model_id'      => 'required|exists:brand_models,id',
            'serial'              => 'required|unique:computers,serial',
            'processor'           => 'nullable|string',
            'ram'                 => 'nullable|string',
            'operating_system_id' => 'nullable|exists:operating_systems,id',
            'department_id'       => 'nullable|exists:departments,id',
            'employee_id'         => 'nullable|exists:employees,id',
            
            // Validamos la estructura del array dinámico que viene de la vista
            'drives'                  => 'nullable|array',
            'drives.*.drive_type_id'  => 'required|exists:drive_types,id',
            'drives.*.brand_model_id' => 'required|exists:brand_models,id',
            'drives.*.cap_number'     => 'required|integer|min:1',
            'drives.*.cap_unit'       => 'required|in:MB,GB,TB',
        ]);

        $computer = Computer::create($validated);

        if ($request->has('drives')) {
            foreach ($request->drives as $driveData) {
                $computer->drives()->create([
                    'drive_type_id'  => $driveData['drive_type_id'],
                    'brand_model_id' => $driveData['brand_model_id'],
                    'capacity_value' => $driveData['cap_number'],
                    'capacity_unit'  => $driveData['cap_unit'],
                    'capacity_in_mb' => $this->calculateMb($driveData['cap_number'], $driveData['cap_unit']),
                ]);
            }
        }

        return redirect()->route('computers.index')
                         ->with('success', 'Equipo y componentes registrados con éxito.');
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
            'images',
        ]);
        return view('computers.show', compact('computer'));
    }

    public function edit(Computer $computer): View
    {
        $computer->load(['drives.driveType', 'drives.brandModel.brand', 'images']);

        [$brandModels, $departments, $employees, $operatingSystems, $driveTypes, $driveModels] = $this->formData();

        return view('computers.edit', compact(
            'computer', 'brandModels', 'departments', 'employees', 'operatingSystems', 'driveTypes', 'driveModels'
        ));
    }

    public function update(Request $request, Computer $computer): RedirectResponse
    {
        $validated = $this->validateComputer($request, $computer->id);
        $this->validateDrives($request);

        $request->validate([
            'images.*' => ['nullable', 'image', 'max:4096'],
        ]);

        DB::transaction(function () use ($request, $validated, $computer) {
            $computer->update($validated);

            // Sincronización básica de nuevos discos si se envían desde un formulario en lote
            if ($request->has('drives')) {
                foreach ($request->input('drives', []) as $driveData) {
                    Drive::create([
                        'computer_id'    => $computer->id,
                        'drive_type_id'  => $driveData['drive_type_id'],
                        'brand_model_id' => $driveData['brand_model_id'],
                        'capacity_value' => $driveData['cap_number'],
                        'capacity_unit'  => $driveData['cap_unit'],
                        'capacity_in_mb' => $this->calculateMb($driveData['cap_number'], $driveData['cap_unit']),
                    ]);
                }
            }

            // Nuevas imágenes
            foreach ($request->file('images', []) as $file) {
                $path = $file->store('computers/' . $computer->id, 'public');
                Image::create(['path' => $path, 'computer_id' => $computer->id]);
            }
        });

        return redirect()->route('computers.show', $computer)->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Computer $computer): RedirectResponse
    {
        foreach ($computer->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $computer->delete();

        return redirect()->route('computers.index')->with('success', 'Equipo eliminado correctamente.');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function validateComputer(Request $request, ?int $computerId = null): array
    {
        return $request->validate([
            'brand_model_id'      => ['required', 'exists:brand_models,id'],
            'serial'              => ['required', 'string', 'max:255', 'unique:computers,serial' . ($computerId ? ',' . $computerId : '')],
            'department_id'       => ['nullable', 'exists:departments,id'],
            'processor'           => ['nullable', 'string', 'max:255'],
            'ram'                 => ['nullable', 'string', 'max:255'],
            'employee_id'         => ['nullable', 'exists:employees,id'],
            'operating_system_id' => ['nullable', 'exists:operating_systems,id'],
        ]);
    }

    private function validateDrives(Request $request): void
    {
        $request->validate([
            'drives'                  => ['nullable', 'array'],
            'drives.*.drive_type_id'  => ['required', 'exists:drive_types,id'],
            'drives.*.brand_model_id' => ['required', 'exists:brand_models,id'],
            'drives.*.cap_number'     => ['required', 'integer', 'min:1'],
            'drives.*.cap_unit'       => ['required', 'in:MB,GB,TB'],
        ]);
    }

    private function formData(): array
    {
        return [
            BrandModel::with('brand')->where('type', 'computer')->orderBy('name')->get(),
            Department::orderBy('name')->get(),
            Employee::orderBy('last_name')->get(),
            OperatingSystem::orderBy('name')->get(),
            DriveType::orderBy('name')->get(),
            BrandModel::with('brand')->where('type', 'drive')->orderBy('name')->get(),
        ];
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