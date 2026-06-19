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
    public function index(): View
    {
        $employees = Employee::with('department')->orderBy('last_name')->paginate(15);
 
        return view('employees.index', compact('employees'));
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
