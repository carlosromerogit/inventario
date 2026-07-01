<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandModel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class BrandModelController extends Controller
{
    //
    public function index(): View
    {
        $brandModels = BrandModel::with('brand')->orderBy('name')->paginate(15);
 
        return view('brand-models.index', compact('brandModels'));
    }
 
    public function create(): View
    {
        $brands = Brand::orderBy('name')->get();
 
        return view('brand-models.create', compact('brands'));
    }
 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name'     => 'required|string|max:255',
            'type'     => 'required|in:computer,drive', // Agrega esta regla
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brand_models')
                    ->where(fn ($query) => $query->where('brand_id', $request->brand_id))
                    ->ignore($brandModel->id),
            ],
        ]);
 
        $brandModel->update($validated);
 
        return redirect()->route('brand-models.index')->with('success', 'Modelo actualizado correctamente.');
    }
 
    public function destroy(BrandModel $brandModel): RedirectResponse
    {
        $brandModel->delete();
 
        return redirect()->route('brand-models.index')->with('success', 'Modelo eliminado correctamente.');
    }

}
