<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandModel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class BrandModelController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:brand_models.index', only: ['index']),
            new Middleware('permission:brand_models.create', only: ['create']),
            new Middleware('permission:brand_models.store', only: ['store']),
            new Middleware('permission:brand_models.show', only: ['show']),
            new Middleware('permission:brand_models.edit', only: ['edit']),
            new Middleware('permission:brand_models.update', only: ['update']),
            new Middleware('permission:brand_models.destroy', only: ['destroy']),
        ];
    }


    public function index(Request $request): View
    {
    $query = BrandModel::with('brand');

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('brand_id')) {
        $query->where('brand_id', $request->input('brand_id'));
    }

    $brandModels = $query->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    $brands = Brand::orderBy('name')->get();

    return view('brand-models.index', compact('brandModels', 'brands'));
    }
 
    public function create(): View
    {
        $brands = Brand::orderBy('name')->get();
 
        return view('brand-models.create', compact('brands'));
    }
 
    public function store(Request $request)
    {
      $validated = $request->validate([
            'brand_id' => ['required', 'exists:brands,id'],
            'type'     => ['required', 'in:computer,drive'],
            'name'     => [
                'required',
                'string',
                'max:255',
                Rule::unique('brand_models')
                    ->where(fn ($query) => $query->where('brand_id', $request->brand_id)->where('type', $request->type))
            ],
        ], [], [
            'brand_id' => 'marca',
            'type'     => 'tipo de dispositivo',
            'name'     => 'nombre del modelo',
        ]);

        BrandModel::create($validated);

        return redirect()->route('brand-models.index')->with('success', 'Modelo creado con éxito.');
    }
 
    public function show(BrandModel $brandModel): View
    {
        $brandModel->load('brand');
 
        return view('brand-models.show', compact('brandModel'));
    }
 
    public function edit(BrandModel $brandModel): View
    {
        $brands = Brand::orderBy('name')->get();
 
        return view('brand-models.edit', compact('brandModel', 'brands'));
    }
 
    public function update(Request $request, BrandModel $brandModel): RedirectResponse
    {
      $validated = $request->validate([
            'brand_id' => ['required', 'exists:brands,id'],
            'type'     => ['required', 'in:computer,drive'],
            'name'     => [
                'required',
                'string',
                'max:255',
                Rule::unique('brand_models')
                    ->where(fn ($query) => $query->where('brand_id', $request->brand_id)->where('type', $request->type))
                    ->ignore($brandModel->id),
            ],
        ], [], [
            'brand_id' => 'marca',
            'type'     => 'tipo de dispositivo',
            'name'     => 'nombre del modelo',
        ]);
        
        $brandModel->update($validated);
 
        return redirect()->route('brand-models.index')->with('success', 'Modelo actualizado correctamente.');
    }
 
    public function destroy(BrandModel $brandModel): RedirectResponse
    {
        if ($brandModel->computers()->exists()) {
            return redirect()->route('brand-models.index')->with('error', 'No se puede eliminar el modelo porque tiene computadoras asociadas en el inventario.');
        }

        $brandModel->delete();

        return redirect()->route('brand-models.index')->with('success', 'Modelo eliminado correctamente.');
    }

}
