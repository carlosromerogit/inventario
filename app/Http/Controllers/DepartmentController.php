<?php

namespace App\Http\Controllers;


use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

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
        $query = Department::query();

    // 🔎 Único Filtro: Búsqueda por Nombre
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Paginación limpia manteniendo la búsqueda activa al cambiar de página
    $departments = $query->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    return view('departments.index', compact('departments'));

    return view('departments.index', compact('departments'));
    }
 
    public function create(): View
    {
        return view('departments.create');
    }
 
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
        ]);
 
        Department::create($validated);
 
        return redirect()->route('departments.index')->with('success', 'Departamento creado correctamente.');
    }
 
    public function show(Department $department): View
    {
        return view('departments.show', compact('department'));
    }
 
    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }
 
    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name,' . $department->id],
        ]);
 
        $department->update($validated);
 
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
