<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    //
    public function index(Request $request): View
    {
    // Iniciamos la consulta base con sus relaciones
    $query = Employee::with(['department', 'computers']);

    // 1. Filtro por Texto: Nombre o Apellido
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%");
        });
    }

    // 2. Filtro por Departamento
    if ($request->filled('department_id')) {
        $query->where('department_id', $request->input('department_id'));
    }

    // 3. Filtro por Estado de Hardware (¿Tiene o no PC?)
    if ($request->filled('has_computer')) {
        if ($request->input('has_computer') === 'yes') {
            $query->has('computers'); // Empleados con al menos 1 computadora
        } elseif ($request->input('has_computer') === 'no') {
            $query->doesntHave('computers'); // Empleados sin computadoras
        }
    }

    // Ordenamos por apellido, paginamos y adjuntamos las variables de la URL
    $employees = $query->orderBy('last_name')
                       ->paginate(15)
                       ->withQueryString();

    // Cargamos los departamentos para el selector del formulario
    $departments = Department::orderBy('name')->get();

    return view('employees.index', compact('employees', 'departments'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
 
        return view('employees.create', compact('departments'));
    }
 
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);
 
        Employee::create($validated);
 
        return redirect()->route('employees.index')->with('success', 'Empleado creado correctamente.');
    }
 
    public function show(Employee $employee): View
    {
        $employee->load('department', 'computers');
 
        return view('employees.show', compact('employee'));
    }
 
    public function edit(Employee $employee): View
    {
        $departments = Department::orderBy('name')->get();
 
        return view('employees.edit', compact('employee', 'departments'));
    }
 
    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);
 
        $employee->update($validated);
 
        return redirect()->route('employees.index')->with('success', 'Empleado actualizado correctamente.');
    }
 
    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();
 
        return redirect()->route('employees.index')->with('success', 'Empleado eliminado correctamente.');
    }

}
