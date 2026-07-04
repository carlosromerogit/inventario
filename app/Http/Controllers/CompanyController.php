<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class CompanyController extends Controller implements HasMiddleware
{

    //
  public static function middleware(): array
    {
        return [
            new Middleware('permission:componies.index', only: ['index', 'show']),
            new Middleware('permission:componies.create', only: ['create', 'store']),
            new Middleware('permission:componies.edit', only: ['edit', 'update']),
            new Middleware('permission:componies.destroy', only: ['destroy']),
        ];
    }
    public function index(): View
    {
        $companies = Company::orderBy('name')->paginate(15);

        return view('companies.index', compact('companies'));
    }

    public function create(): View
    {
        return view('companies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255', 'unique:companies,name'],
            'rnc'     => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        Company::create($validated);

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
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255', 'unique:companies,name,' . $company->id],
            'rnc'     => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $company->update($validated);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()
            ->route('companies.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }
}