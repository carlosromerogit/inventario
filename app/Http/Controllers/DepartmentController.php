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
    // Cargamos la relación 'companies' de antemano para evitar consultas extras (N+1)
    $query = Department::with('companies');

    // 🔎 Filtro 1: Búsqueda por Nombre del Departamento
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // 🏢 Filtro 2: Filtrar por Empresa (Relación Muchos a Muchos)
    if ($request->filled('company_id')) {
        $query->whereHas('companies', function ($q) use ($request) {
            $q->where('companies.id', $request->company_id);
        });
    }

    // Paginación limpia manteniendo los filtros activos
    $departments = $query->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    // Traemos todas las empresas para llenar el <select> en la vista
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
        'companies'   => ['nullable', 'array'], // 👈 Validamos el array de empresas
        'companies.*' => ['exists:companies,id'],
    ]);

    $department = Department::create($validated);

    // 🔗 Sincronizamos en la tabla pivote
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
        'companies'   => ['nullable', 'array'], // 👈 Validamos el array
        'companies.*' => ['exists:companies,id'],
    ]);

    $department->update($validated);

    // 🔄 Actualizamos relaciones
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
