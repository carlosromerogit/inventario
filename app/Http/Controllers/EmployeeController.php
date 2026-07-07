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
use Illuminate\Validation\Rule;
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

        // 🔎 búsqueda
      if ($request->filled('search')) {
    $search = $request->input('search');

    $query->where(function ($q) use ($search) {
        $q->where('first_name', 'like', "%{$search}%")
          ->orWhere('last_name', 'like', "%{$search}%");
    });
}
                if ($request->filled('work_shift')) {
            $query->where('work_shift', $request->input('work_shift'));
        }
        if ($request->filled('employee_code')) {
            $query->where('employee_code', 'like', '%' . $request->employee_code . '%');
        }
        // 🏢 departamento
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        // 🏭 empresa
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        // 💻 equipos
        if ($request->filled('has_computer')) {
            if ($request->input('has_computer') === 'yes') {
                $query->has('computers');
            } elseif ($request->input('has_computer') === 'no') {
                $query->doesntHave('computers');
            }
        }

        // 🕒 turno (NUEVO)
        if ($request->filled('work_shift')) {
            $query->where('work_shift', $request->input('work_shift'));
        }

        $employees = $query->orderBy('last_name')
            ->paginate(10)
            ->withQueryString();

        $departments = Department::orderBy('name')->get();
        $companies    = Company::orderBy('name')->get();

        return view('employees.index', compact('employees', 'departments', 'companies'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        $companies   = Company::orderBy('name')->get();

        return view('employees.create', compact('departments', 'companies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'extension'     => ['nullable', 'string', 'max:255'],
            'email'         => ['nullable', 'email', 'unique:employees,email', 'max:255'],

            'department_id' => ['required', 'exists:departments,id'],
            'company_id'    => ['required', 'exists:companies,id'],
            
            'employee_code' => ['required', 'string', 'unique:employees,employee_code', 'max:10'],

            'work_shift'    => ['nullable', 'in:morning/afternoon,night'],
        ], [
            'employee_code.unique' => 'El código ya está registrado.',
            'employee_code.required' => 'El código es obligatorio.',
        ]);

        Employee::create($validated);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Empleado creado correctamente.');
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

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'extension'     => ['nullable', 'string', 'max:255'],

    // 🏢 Siguen siendo requeridos en la edición
            'department_id' => ['required', 'exists:departments,id'],
            'company_id'    => ['required', 'exists:companies,id'],
            'email'         => ['nullable', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($employee->id)],
            'employee_code' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('employees', 'employee_code')->ignore($employee->id)
            ],
            'work_shift'    => ['nullable', 'in:morning/afternoon,night'],
        ]);

        $employee->update($validated);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Empleado eliminado correctamente.');
    }
}