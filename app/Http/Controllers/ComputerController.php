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
            new Middleware('permission:computers.index', only: ['index']),
            new Middleware('permission:computers.create', only: ['create']),
            new Middleware('permission:computers.store', only: ['store']),
            new Middleware('permission:computers.show', only: ['show']),
            new Middleware('permission:computers.edit', only: ['edit']),
            new Middleware('permission:computers.update', only: ['update']),
            new Middleware('permission:computers.destroy', only: ['destroy']),
        ];
    }


  public function index(Request $request)
{
    $query = Computer::with([
        'brandModel.brand',
        'department',
        'employee',
        'operatingSystem',
        'company'
    ]);

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
    if ($request->filled('brand_or_model')) {
    $value = $request->input('brand_or_model');

    if (str_starts_with($value, 'brand_')) {
        $brandId = str_replace('brand_', '', $value);
        
        $query->whereHas('brandModel', function ($q) use ($brandId) {
            $q->where('brand_id', $brandId);
        });
    } 
    elseif (str_starts_with($value, 'model_')) {
        $modelId = str_replace('model_', '', $value);
        
        $query->where('brand_model_id', $modelId);
    }
}

    // if ($request->filled('brand_id')) {
    //     $query->whereHas('brandModel', function ($q) use ($request) {
    //         $q->where('brand_id', $request->brand_id);
    //     });
    // }

    // if ($request->filled('brand_model_id')) {
    //     $query->where('brand_model_id', $request->brand_model_id);
    // }

    if ($request->filled('company_or_department')) {
    $value = $request->input('company_or_department');

    if (str_starts_with($value, 'company_')) {
        $companyId = str_replace('company_', '', $value);
        
        $query->where('company_id', $companyId);
    } 
    elseif (str_starts_with($value, 'comp_')) {
        preg_match('/comp_(\d+)_dept_(\d+)/', $value, $matches);
        
        if (count($matches) === 3) {
            $companyId = $matches[1];
            $departmentId = $matches[2];

            $query->where('company_id', $companyId)
                  ->where('department_id', $departmentId);
        }
    }
}

    if ($request->filled('company_id')) {
        $query->where('company_id', $request->company_id);
    }

    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }

    if ($request->filled('operating_system_id')) {
        $query->where('operating_system_id', $request->operating_system_id);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('ram')) {
        $query->where('ram', 'like', '%' . $request->ram . '%');
    }

    if (
        $request->filled('drive_type_id') ||
        $request->filled('drive_brand_model_id') ||
        $request->filled('capacity_value')
    ) {
        $query->whereHas('drives', function ($q) use ($request) {
            if ($request->filled('drive_type_id')) {
                $q->where('drive_type_id', $request->drive_type_id);
            }

            if ($request->filled('drive_brand_model_id')) {
                $q->where('brand_model_id', $request->drive_brand_model_id);
            }

            if ($request->filled('capacity_value')) {
                $capacity = match ($request->capacity_unit) {
                    'TB' => $request->capacity_value * 1024 * 1024,
                    'GB' => $request->capacity_value * 1024,
                    default => $request->capacity_value,
                };

                $operator = $request->capacity_operator ?? '>=';
                $q->where('capacity_in_mb', $operator, $capacity);
            }
        });
    }

    if ($request->input('export') === 'pdf') {
        $computersToExport = $query->with(['brandModel.brand', 'employee', 'company', 'operatingSystem'])
            ->orderBy('serial')
            ->get();

        $filtrosAplicados = [];

        if ($request->filled('search')) {
            $filtrosAplicados['Búsqueda'] = '"' . $request->search . '"';
        }
        if ($request->filled('brand_id')) {
            $brand = \App\Models\Brand::find($request->brand_id);
            $filtrosAplicados['Marca'] = $brand ? $brand->name : 'Desconocida';
        }
        if ($request->filled('brand_model_id')) {
            $bModel = \App\Models\BrandModel::with('brand')->find($request->brand_model_id);
            $filtrosAplicados['Modelo'] = $bModel ? "{$bModel->brand->name} {$bModel->name}" : 'Desconocido';
        }
        if ($request->filled('ram')) {
            $filtrosAplicados['RAM'] = $request->ram;
        }
        if ($request->filled('department_id')) {
            $dept = \App\Models\Department::find($request->department_id);
            $filtrosAplicados['Departamento'] = $dept ? $dept->name : 'Desconocido';
        }
        if ($request->filled('company_id')) {
            $comp = \App\Models\Company::find($request->company_id);
            $filtrosAplicados['Empresa'] = $comp ? $comp->name : 'Desconocida';
        }
        if ($request->filled('operating_system_id')) {
            $os = \App\Models\OperatingSystem::find($request->operating_system_id);
            $filtrosAplicados['S.O.'] = $os ? $os->name : 'Desconocido';
        }
        if ($request->filled('status')) {
            $estados = ['assigned' => 'Asignado', 'stock' => 'En Stock', 'faulty' => 'Averiado', 'obsolete' => 'Obsoleto'];
            $filtrosAplicados['Estado'] = $estados[$request->status] ?? $request->status;
        }
        if ($request->filled('drive_type_id')) {
            $dType = \App\Models\DriveType::find($request->drive_type_id);
            $filtrosAplicados['Tipo Disco'] = $dType ? $dType->name : '';
        }
        if ($request->filled('capacity_value')) {
            $op = $request->capacity_operator ?? '>=';
            $filtrosAplicados['Capacidad Disco'] = "{$op} {$request->capacity_value} {$request->capacity_unit}";
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.index_computers', compact('computersToExport', 'filtrosAplicados'));
        $pdf->setPaper('letter', 'landscape');
        $fileName = 'reporte_computadoras_' . date('Y-m-d_H-i') . '.pdf';
        
        return $pdf->download($fileName);
    }

    $computers = $query->orderBy('serial')
        ->paginate(15)
        ->withQueryString();

    $driveTypes = \App\Models\DriveType::orderBy('name')->get();

    $driveModels = BrandModel::with('brand')
        ->where('type', 'drive')
        ->orderBy('name')
        ->get();

    $computerModels = BrandModel::with('brand')
        ->where('type', 'computer')
        ->orderBy('name')
        ->get();

    return view('computers.index', [
        'computers'        => $computers,
        'brands'           => \App\Models\Brand::orderBy('name')->get(),
        'departments'      => \App\Models\Department::orderBy('name')->get(),
        'operatingSystems' => \App\Models\OperatingSystem::orderBy('name')->get(),
        'companies'        => \App\Models\Company::orderBy('name')->get(),
        'driveTypes'       => $driveTypes,
        'driveModels'      => $driveModels,
        'computerModels'   => $computerModels,
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
        'warranty'
    ]);

    return view('computers.show', compact('computer'));
}
public function store(Request $request): RedirectResponse
{
    $this->parseCompanyAndDepartment($request);

    $validated = $this->validateComputer($request);

    DB::transaction(function () use ($request, $validated, &$computer) {
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

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('computers', 'public');
                $computer->images()->create([
                    'path' => $path,
                ]);
            }
        }
    });

    if ($request->filled('seller') || $request->filled('purchase_order') || $request->hasFile('purchase_order_pdf')) {
        $pdfPath = null;
        if ($request->hasFile('purchase_order_pdf')) {
            $pdfPath = $request->file('purchase_order_pdf')->store('warranties', 'public');
        }

        $computer->warranty()->create([
            'seller' => $request->input('seller'),
            'purchase_order' => $request->input('purchase_order'),
            'purchase_order_pdf_path' => $pdfPath,
            'start_date' => $request->input('warranty_start_date'), 
            'end_date' => $request->input('warranty_end_date'),  
        ]);
    }

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
    $this->parseCompanyAndDepartment($request);

    $validated = $this->validateComputer($request, $computer->id);

    $this->validateDrives($request);

    DB::transaction(function () use ($request, $validated, $computer) {
        $computer->update($validated);

        if ($request->filled('drives')) {
            foreach ($request->input('drives', []) as $driveData) {
                $computer->drives()->create([
                    'drive_type_id'   => $driveData['drive_type_id'],
                    'brand_model_id'  => $driveData['brand_model_id'],
                    'capacity_value'  => $driveData['cap_number'],
                    'capacity_unit'   => $driveData['cap_unit'],
                    'capacity_in_mb'  => $this->calculateMb($driveData['cap_number'], $driveData['cap_unit']),
                ]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('computers/' . $computer->id, 'public');
                Image::create([
                    'path' => $path,
                    'computer_id' => $computer->id
                ]);
            }
        }

        if ($request->filled('seller') || $request->filled('purchase_order') || $request->hasFile('purchase_order_pdf')) {
        
        $warrantyData = [
            'seller' => $request->input('seller'),
            'purchase_order' => $request->input('purchase_order'),
            'start_date' => $request->input('warranty_start_date'),
            'end_date' => $request->input('warranty_end_date'),
        ];

        if ($request->hasFile('purchase_order_pdf')) {
            if ($computer->warranty && $computer->warranty->purchase_order_pdf_path) {
                Storage::disk('public')->delete($computer->warranty->purchase_order_pdf_path);
            }
            $warrantyData['purchase_order_pdf_path'] = $request->file('purchase_order_pdf')->store('warranties', 'public');
        }

        $computer->warranty()->updateOrCreate([], $warrantyData);
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
        
        'department_id'       => ['required', 'exists:departments,id'],
        'company_id'          => ['required', 'exists:companies,id'],
        
        'employee_id'         => ['nullable', 'exists:employees,id'],
        'processor'           => ['nullable', 'string'],
        'ram'                 => ['nullable', 'string'],
        'hostname'            => ['nullable', 'string', 'unique:computers,hostname,' . $id],
        'fixed_asset'         => ['nullable', 'string', 'unique:computers,fixed_asset,' . $id],
        'operating_system_id' => ['nullable', 'exists:operating_systems,id'],
        'status'              => ['required', 'in:stock,assigned,faulty,obsolete'],

        'drives'                  => ['nullable', 'array'],
        'drives.*.drive_type_id'  => ['required_with:drives', 'exists:drive_types,id'],
        'drives.*.brand_model_id' => ['required_with:drives', 'exists:brand_models,id'],
        'drives.*.cap_number'     => ['required_with:drives', 'integer', 'min:1'],
        'drives.*.cap_unit'       => ['required_with:drives', 'in:MB,GB,TB'],

        'seller'              => ['nullable', 'string', 'max:255'],
        'purchase_order'      => ['nullable', 'string', 'max:255'],
        'purchase_order_pdf'  => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        'warranty_start_date' => ['nullable', 'date'],
        'warranty_end_date'   => ['nullable', 'date', 'after_or_equal:warranty_start_date'],
    ],
    [],
    [
        'brand_model_id'          => 'modelo de marca',
        'serial'                  => 'número de serie',
        'department_id'           => 'departamento',
        'company_id'              => 'empresa',
        'employee_id'             => 'empleado',
        'processor'               => 'procesador',
        'ram'                     => 'memoria RAM',
        'hostname'                => 'nombre de host (hostname)',
        'fixed_asset'             => 'activo fijo',
        'operating_system_id'     => 'sistema operativo',
        'status'                  => 'estado',

        'drives'                  => 'unidades de almacenamiento',
        'drives.*.drive_type_id'  => 'tipo de disco',
        'drives.*.brand_model_id' => 'modelo del disco',
        'drives.*.cap_number'     => 'capacidad del disco',
        'drives.*.cap_unit'       => 'unidad de capacidad',

        'seller'                  => 'vendedor / proveedor',
        'purchase_order'          => 'orden de compra',
        'purchase_order_pdf'      => 'PDF de orden de compra',
        'warranty_start_date'     => 'inicio de garantía',
        'warranty_end_date'       => 'fin de garantía',
    ]
    );
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

        // private function formData(): array
        // {
        //     return [
        //         BrandModel::with('brand')->where('type', 'computer')->orderBy('name')->get(),
        //         Department::orderBy('name')->get(),
        //         Employee::orderBy('last_name')->get(),
        //         OperatingSystem::orderBy('name')->get(),
        //         DriveType::orderBy('name')->get(),
        //         BrandModel::with('brand')->where('type', 'drive')->orderBy('name')->get(),
        //     ];
        // }

    private function calculateMb(int $value, string $unit): int
    {
        return match ($unit) {
            'TB' => $value * 1024 * 1024,
            'GB' => $value * 1024,
            default => $value,
        };
    }

    private function parseCompanyAndDepartment(Request $request): void
    {
        if ($request->filled('company_and_department')) {
            $parts = explode('-', $request->input('company_and_department'));
            if (count($parts) === 2) {
                $request->merge([
                    'company_id' => $parts[0],
                    'department_id' => $parts[1]
                ]);
            }
        }
    }

    public function getEmployeesByLocation(Request $request)
    {
        [$companyId, $departmentId] = explode('-', $request->query('location', '-'));

        if (!$companyId || !$departmentId) {
            return response()->json([]);
        }

        $employees = Employee::where('company_id', $companyId)
            ->where('department_id', $departmentId)
            ->select('id', 'first_name', 'last_name')
            ->get();

        return response()->json($employees);
    }
    }