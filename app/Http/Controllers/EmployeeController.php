<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class EmployeeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:employees.index', only: ['index']),
            new Middleware('permission:employees.create', only: ['create']),
            new Middleware('permission:employees.store', only: ['store']),
            new Middleware('permission:employees.show', only: ['show']),
            new Middleware('permission:employees.edit', only: ['edit']),
            new Middleware('permission:employees.update', only: ['update']),
            new Middleware('permission:employees.destroy', only: ['destroy']),
        ];
    }


    public function index(Request $request): View
{
    $query = Employee::with(['department', 'company', 'computers']);

    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%");
        });
    }

    if ($request->filled('employee_code')) {
        $query->where('employee_code', 'like', '%' . $request->employee_code . '%');
    }

    if ($request->filled('work_shift')) {
        $query->where('work_shift', $request->input('work_shift'));
    }

    if ($request->filled('company_or_department')) {
        $filterValue = $request->input('company_or_department');

        if (str_starts_with($filterValue, 'company_')) {
            $companyId = str_replace('company_', '', $filterValue);
            $query->where('company_id', $companyId);
            
        } elseif (str_starts_with($filterValue, 'comp_')) {
            if (preg_match('/comp_(\d+)_dept_(\d+)/', $filterValue, $matches)) {
                $companyId = $matches[1];
                $departmentId = $matches[2];
                
                $query->where('company_id', $companyId)
                      ->where('department_id', $departmentId);
            }
        }
    }

    if ($request->filled('has_computer')) {
        if ($request->input('has_computer') === 'yes') {
            $query->has('computers');
        } elseif ($request->input('has_computer') === 'no') {
            $query->doesntHave('computers');
        }
    }

    $employees = $query->orderBy('last_name')
        ->paginate(10)
        ->withQueryString();

    $companies = Company::with('departments')->orderBy('name')->get();

    return view('employees.index', compact('employees', 'companies'));
}

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        $companies   = Company::orderBy('name')->get();

        return view('employees.create', compact('departments', 'companies'));
    }

    public function store(Request $request)
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

       $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'company_id'    => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'employee_code' => 'required|string|max:50|unique:employees,employee_code', 
            'email'         => 'nullable|email|max:255',
            'extension'     => 'nullable|string',
            'work_shift'    => 'nullable|string',
        ], [], [
            'first_name'    => 'nombre',
            'last_name'     => 'apellido',
            'company_id'    => 'empresa',
            'department_id' => 'departamento',
            'employee_code' => 'código de empleado',
            'email'         => 'correo electrónico',
            'extension'     => 'extensión',
            'work_shift'    => 'turno de trabajo',
        ]);

    Employee::create($validated);

    return redirect()->route('employees.index')->with('success', 'Empleado creado con éxito.');
}

    public function show(Employee $employee): View
    {
        $employee->load(['department', 'company', 'computers']);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $departments = Department::orderBy('name')->get();
        $companies   = Company::orderBy('name')->get();

        return view('employees.edit', compact('employee', 'departments', 'companies'));
    }

    public function update(Request $request, Employee $employee)
{
    // ⚙️ Descomponer el string 'company_id-department_id'
    if ($request->filled('company_and_department')) {
        $parts = explode('-', $request->input('company_and_department'));
        if (count($parts) === 2) {
            $request->merge([
                'company_id' => $parts[0],
                'department_id' => $parts[1]
            ]);
        }
    }

$validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'company_id'    => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'employee_code' => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:255',
            'extension'     => 'nullable|string|max:20',
            'work_shift'    => 'nullable|string|in:morning/afternoon,night',
        ], [], [
            'first_name'    => 'nombre',
            'last_name'     => 'apellido',
            'company_id'    => 'empresa',
            'department_id' => 'departamento',
            'employee_code' => 'código de empleado',
            'email'         => 'correo electrónico',
            'extension'     => 'extensión',
            'work_shift'    => 'turno de trabajo',
        ]);

    $employee->update($validated);

    return redirect()->route('employees.show', $employee)->with('success', 'Empleado actualizado con éxito.');
}

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Empleado eliminado correctamente.');
    }
}