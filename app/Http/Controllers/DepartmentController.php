<?php

namespace App\Http\Controllers;


use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use App\Models\Company;


class DepartmentController extends Controller implements HasMiddleware
{
    //
    public static function middleware(): array
    {
        return [
            new Middleware('permission:departments.index', only: ['index']),
            new Middleware('permission:departments.create', only: ['create']),
            new Middleware('permission:departments.store', only: ['store']),
            new Middleware('permission:departments.show', only: ['show']),
            new Middleware('permission:departments.edit', only: ['edit']),
            new Middleware('permission:departments.update', only: ['update']),
            new Middleware('permission:departments.destroy', only: ['destroy']),
        ];
    }

  public function index(Request $request): View
{
    $query = Department::with('companies');

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('company_id')) {
        $query->whereHas('companies', function ($q) use ($request) {
            $q->where('companies.id', $request->company_id);
        });
    }

    $departments = $query->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    $companies = Company::orderBy('name')->get();

    return view('departments.index', compact('departments', 'companies'));
}
public function create(): View
{
    $companies = Company::orderBy('name')->get();
    
    return view('departments.create', compact('companies'));
}
 
    public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name'        => ['required', 'string', 'max:255', 'unique:departments,name'],
        'companies'   => ['nullable', 'array'],
        'companies.*' => ['exists:companies,id'],
    ]);

    $department = Department::create($validated);

    if ($request->has('companies')) {
        $department->companies()->sync($request->companies);
    }

    return redirect()->route('departments.index')->with('success', 'Departamento creado correctamente.');
}
 
    public function show(Department $department): View
    {
        return view('departments.show', compact('department'));
    }
 
 public function edit(Department $department): View
{
    $companies = Company::orderBy('name')->get();
    $department->load('companies');

    return view('departments.edit', compact('department', 'companies'));
}
 
    public function update(Request $request, Department $department): RedirectResponse
{
    $validated = $request->validate([
        'name'        => ['required', 'string', 'max:255', 'unique:departments,name,' . $department->id],
        'companies'   => ['nullable', 'array'], 
        'companies.*' => ['exists:companies,id'],
    ]);

    $department->update($validated);

    $department->companies()->sync($request->input('companies', []));

    return redirect()->route('departments.index')->with('success', 'Departamento actualizado correctamente.');
}
 
    public function destroy(Department $department): RedirectResponse
    {
                if ($department->employees()->exists()) {
            return redirect()->route('departments.index')
                ->with('error', 'No se puede eliminar el departamento porque tiene empleados asignados.');
        }
        $department->delete();
 
        return redirect()->route('departments.index')->with('success', 'Departamento eliminado correctamente.');
    }

}
