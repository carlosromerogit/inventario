<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\Department;

class CompanyController extends Controller implements HasMiddleware
{

    //
    public static function middleware(): array
    {
        return [
            new Middleware('permission:companies.index', only: ['index']),
            new Middleware('permission:companies.create', only: ['create']),
            new Middleware('permission:companies.store', only: ['store']),
            new Middleware('permission:companies.show', only: ['show']),
            new Middleware('permission:companies.edit', only: ['edit']),
            new Middleware('permission:companies.update', only: ['update']),
            new Middleware('permission:companies.destroy', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Company::query();

   if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('RNC', 'like', '%' . $request->search . '%');
        });
    }

        $companies = $query->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('companies.index', compact('companies'));
    }

    public function create(): View
    {
        // Obtenemos todos los departamentos para que el usuario pueda seleccionarlos
        $departments = Department::orderBy('name')->get();
        
        return view('companies.create', compact('departments'));
    }

public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name'          => ['required', 'string', 'unique:companies,name', 'max:255'],
        'address'       => ['nullable', 'string', 'max:500'],
        'RNC'           => ['nullable', 'string', 'max:50'],
        'departments'   => ['nullable', 'array'], // 👈 Validamos que llegue un array de IDs
        'departments.*' => ['exists:departments,id'], // Cada ID debe existir
    ]);

    // Creamos la empresa
    $company = Company::create($validated);

    // 🔗 Sincronizamos los departamentos en la tabla pivote
    if ($request->has('departments')) {
        $company->departments()->sync($request->departments);
    }

    return redirect()
        ->route('companies.index')
        ->with('success', 'Empresa creada correctamente.');
}
    public function show(Company $company): View
    {
        $company->load(['employees', 'computers']);

        return view('companies.show', compact('company'));
    }

public function edit(Company $company): View
{
    $departments = Department::orderBy('name')->get();
    
    // Cargamos la relación actual para saber cuáles tiene asignados
    $company->load('departments');

    return view('companies.edit', compact('company', 'departments'));
}
   public function update(Request $request, Company $company): RedirectResponse
{
    $validated = $request->validate([
        'name'          => ['required', 'string', 'max:255', Rule::unique('companies', 'name')->ignore($company->id)],
        'address'       => ['nullable', 'string', 'max:500'],
        'RNC'           => ['nullable', 'string', 'max:50'],
        'departments'   => ['nullable', 'array'], // 👈 Validamos el array
        'departments.*' => ['exists:departments,id'],
    ]);

    $company->update($validated);

    // 🔄 Sincronizamos (esto añade los nuevos y remueve los que se desmarcaron)
    $company->departments()->sync($request->input('departments', []));

    return redirect()
        ->route('companies.index')
        ->with('success', 'Empresa actualizada correctamente.');
}
    public function destroy(Company $company): RedirectResponse
    {
       if ($company->employees()->exists()) {
        return redirect()->route('companies.index')
            ->with('error', 'No se puede eliminar la empresa porque tiene empleados asignados.');
    }
        $company->delete();

        return redirect()
            ->route('companies.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }
}