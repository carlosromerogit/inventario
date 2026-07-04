<?php

namespace App\Http\Controllers;

use App\Models\BrandModel;
use App\Models\Computer;
use App\Models\Department;
use App\Models\DriveType;
use App\Models\Employee;
use App\Models\Image;
use App\Models\OperatingSystem;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ComputerController extends Controller implements HasMiddleware
{

  public static function middleware(): array
    {
        return [
            new Middleware('permission:brand_models.index', only: ['index', 'show']),
            new Middleware('permission:brand_models.create', only: ['create', 'store']),
            new Middleware('permission:brand_models.edit', only: ['edit', 'update']),
            new Middleware('permission:brand_models.destroy', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
{
    $query = Computer::with([
        'brandModel.brand',
        'department',
        'employee',
        'operatingSystem',
        'company'
    ]);

    // 🔎 SEARCH
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('serial', 'like', "%{$search}%")
              ->orWhereHas('employee', function ($e) use ($search) {
                  $e->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
              });
        });
    }

    // 🏢 COMPANY
    if ($request->filled('company_id')) {
        $query->where('company_id', $request->company_id);
    }

    // 🏢 DEPARTMENT
    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }

    // 💻 OS
    if ($request->filled('operating_system_id')) {
        $query->where('operating_system_id', $request->operating_system_id);
    }

    // 📌 STATUS REAL
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    
        if ($request->filled('ram')) {
                $query->where('ram', 'like', '%' . $request->ram . '%');
            }

    // 💾 FILTROS DE DISCOS
if (
    $request->filled('drive_type_id') ||
    $request->filled('drive_brand_model_id') ||
    $request->filled('capacity_value')
) {

    $query->whereHas('drives', function ($q) use ($request) {

        // Tipo de disco
        if ($request->filled('drive_type_id')) {
            $q->where('drive_type_id', $request->drive_type_id);
        }

        // Marca / Modelo
        if ($request->filled('drive_brand_model_id')) {
            $q->where('brand_model_id', $request->drive_brand_model_id);
        }


        // Capacidad
        if ($request->filled('capacity_value')) {

            $capacity = match ($request->capacity_unit) {
                'TB' => $request->capacity_value * 1024 * 1024,
                'GB' => $request->capacity_value * 1024,
                default => $request->capacity_value,
            };

            $operator = $request->capacity_operator ?? '>=';

            $q->where(
                'capacity_in_mb',
                $operator,
                $capacity
            );
        }

    });

}

    $computers = $query->orderBy('serial')
        ->paginate(15)
        ->withQueryString();

    $driveTypes = DriveType::orderBy('name')->get();

    $driveModels = BrandModel::with('brand')
        ->where('type', 'drive')
        ->orderBy('name')
        ->get();

    return view('computers.index', [
        'computers' => $computers,
        'brands' => \App\Models\Brand::orderBy('name')->get(),
        'departments' => \App\Models\Department::orderBy('name')->get(),
        'operatingSystems' => \App\Models\OperatingSystem::orderBy('name')->get(),
        'companies' => \App\Models\Company::orderBy('name')->get(),

        'driveTypes' => $driveTypes,
        'driveModels' => $driveModels,
    ]);
}
    public function create(): View
{
    $brandModels = \App\Models\BrandModel::with('brand')
        ->where('type', 'computer')
        ->orderBy('name')
        ->get();

    $departments = \App\Models\Department::orderBy('name')->get();
    $employees = \App\Models\Employee::orderBy('last_name')->get();
    $operatingSystems = \App\Models\OperatingSystem::orderBy('name')->get();
    $companies = \App\Models\Company::orderBy('name')->get();

    $driveTypes = \App\Models\DriveType::orderBy('name')->get();

    $driveModels = \App\Models\BrandModel::with('brand')
        ->where('type', 'drive')
        ->orderBy('name')
        ->get();

    return view('computers.create', compact(
        'brandModels',
        'departments',
        'employees',
        'operatingSystems',
        'driveTypes',
        'driveModels',
        'companies'
    ));
}
public function show(Computer $computer): View
{
    $computer->load([
        'brandModel.brand',
        'department',
        'employee',
        'operatingSystem',
        'company',
        'drives.driveType',
        'drives.brandModel.brand',
        'images',
    ]);

    return view('computers.show', compact('computer'));
}
public function store(Request $request): RedirectResponse
{
    $validated = $this->validateComputer($request);

    DB::transaction(function () use ($request, $validated) {

        $computer = Computer::create($validated);

        foreach ($request->drives ?? [] as $driveData) {
            $computer->drives()->create([
                'drive_type_id' => $driveData['drive_type_id'],
                'brand_model_id' => $driveData['brand_model_id'],
                'capacity_value' => $driveData['cap_number'],
                'capacity_unit' => $driveData['cap_unit'],
                'capacity_in_mb' => $this->calculateMb($driveData['cap_number'], $driveData['cap_unit']),
            ]);
        }
    });

    return redirect()->route('computers.index')
        ->with('success', 'Equipo creado correctamente.');
}

public function edit(Computer $computer): View
{
    $computer->load([
        'brandModel.brand',
        'department',
        'employee',
        'operatingSystem',
        'company',
        'drives.driveType',
        'drives.brandModel.brand',
        'images',
    ]);

    return view('computers.edit', [
        'computer' => $computer,
        'brandModels' => BrandModel::with('brand')->where('type', 'computer')->orderBy('name')->get(),
        'departments' => Department::orderBy('name')->get(),
        'employees' => Employee::orderBy('last_name')->get(),
        'operatingSystems' => OperatingSystem::orderBy('name')->get(),
        'companies' => Company::orderBy('name')->get(),
        'driveTypes' => DriveType::orderBy('name')->get(),
        'driveModels' => BrandModel::with('brand')->where('type', 'drive')->orderBy('name')->get(),
    ]);
}

    public function update(Request $request, Computer $computer): RedirectResponse
{
    $validated = $this->validateComputer($request, $computer->id);

    $this->validateDrives($request);

    DB::transaction(function () use ($request, $validated, $computer) {

        // 🔄 actualizar equipo
        $computer->update($validated);

        // 💾 DRIVES (evitar duplicados si esto no es lo que quieres)
        if ($request->filled('drives')) {
            foreach ($request->input('drives', []) as $driveData) {
                $computer->drives()->create([
                    'drive_type_id'   => $driveData['drive_type_id'],
                    'brand_model_id'  => $driveData['brand_model_id'],
                    'capacity_value'  => $driveData['cap_number'],
                    'capacity_unit'   => $driveData['cap_unit'],
                    'capacity_in_mb'  => $this->calculateMb(
                        $driveData['cap_number'],
                        $driveData['cap_unit']
                    ),
                ]);
            }
        }

        // 🖼️ IMÁGENES
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('computers/' . $computer->id, 'public');

                Image::create([
                    'path' => $path,
                    'computer_id' => $computer->id
                ]);
            }
        }
    });

    return redirect()
        ->route('computers.show', $computer)
        ->with('success', 'Equipo actualizado correctamente.');
}

    public function destroy(Computer $computer): RedirectResponse
    {
        foreach ($computer->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $computer->delete();

        return redirect()
            ->route('computers.index')
            ->with('success', 'Equipo eliminado correctamente.');
    }

    private function validateComputer(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'brand_model_id'      => ['required', 'exists:brand_models,id'],
            'serial'              => ['required', 'string', 'max:255', 'unique:computers,serial,' . $id],
            'department_id'       => ['nullable', 'exists:departments,id'],
            'company_id'          => ['nullable', 'exists:companies,id'],
            'employee_id'         => ['nullable', 'exists:employees,id'],
            'processor'           => ['nullable', 'string'],
            'ram'                 => ['nullable', 'string'],
            'hostname'                 => ['nullable', 'string'],
            'fixed_asset'                 => ['nullable', 'string', 'unique:computers,fixed_asset,'. $id],
            'operating_system_id' => ['nullable', 'exists:operating_systems,id'],
            'status' => [ 'required', 'in:stock,assigned,faulty,obsolete'],
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
            'TB' => $value * 1024 * 1024,
            'GB' => $value * 1024,
            default => $value,
        };
    }
}