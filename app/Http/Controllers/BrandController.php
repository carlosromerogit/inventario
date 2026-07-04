<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class BrandController extends Controller implements HasMiddleware
{
    //
      public static function middleware(): array
    {
        return [
            new Middleware('permission:brands.index', only: ['index', 'show']),
            new Middleware('permission:brands.create', only: ['create', 'store']),
            new Middleware('permission:brands.edit', only: ['edit', 'update']),
            new Middleware('permission:brands.destroy', only: ['destroy']),
        ];
    }

    
    public function index(): View
    {
        $brands = Brand::orderBy('name')->paginate(15);
 
        return view('brands.index', compact('brands'));
    }
 
    public function create(): View
    {
        return view('brands.create');
    }
 
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
        ]);
 
        Brand::create($validated);
 
        return redirect()->route('brands.index')->with('success', 'Marca creada correctamente.');
    }
 
    public function show(Brand $brand): View
    {
        $brand->load('brandModels');
 
        return view('brands.show', compact('brand'));
    }
 
    public function edit(Brand $brand): View
    {
        return view('brands.edit', compact('brand'));
    }
 
    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name,' . $brand->id],
        ]);
 
        $brand->update($validated);
 
        return redirect()->route('brands.index')->with('success', 'Marca actualizada correctamente.');
    }
 
    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();
 
        return redirect()->route('brands.index')->with('success', 'Marca eliminada correctamente.');
    }

}
