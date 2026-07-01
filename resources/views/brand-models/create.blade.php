@extends('layouts.app')

@section('title', 'Nuevo modelo')
@section('header', 'Nuevo modelo de marca')

@section('content')
<div class="max-w-xl">
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">

        <form action="{{ route('brand-models.store') }}" method="POST">
            @csrf

            <div class="space-y-5">
                {{-- Selector de Marca existente --}}
                <x-select 
                    name="brand_id" 
                    label="Marca" 
                    :options="$brands->pluck('name', 'id')" 
                    :selected="old('brand_id')" 
                    required 
                    placeholder="Seleccione una marca..." />

                {{-- Nombre del Modelo --}}
                <x-input 
                    name="name" 
                    label="Nombre del modelo" 
                    :value="old('name')" 
                    placeholder="Ej. OptiPlex 7080 o A400 SSD" 
                    required />

                {{-- NUEVO: Selector de Tipo de Componente --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Tipo de componente <span class="text-red-500">*</span>
                    </label>
                    <select name="type" 
                            required
                            class="block w-full rounded-md shadow-sm text-sm px-3 py-2 border border-slate-300 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" disabled @selected(!old('type'))>Seleccione el tipo...</option>
                        <option value="computer" @selected(old('type') == 'computer')>Computadora / Laptop</option>
                        <option value="drive" @selected(old('type') == 'drive')>Disco Duro / Almacenamiento</option>
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones de acción --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-slate-100">
                    <a href="{{ route('brand-models.index') }}"
                       class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                        Guardar modelo
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection