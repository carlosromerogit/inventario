@extends('layouts.app')

@section('title', 'Agregar disco')
@section('header', 'Agregar disco')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">

        <form action="{{ route('computers.drives.store', $computer) }}" method="POST">
            @csrf

            <div class="space-y-5">

                {{-- Tipo de Disco --}}
                <x-select 
                    name="drive_type_id" 
                    label="Tipo de disco" 
                    :options="$driveTypes->pluck('name', 'id')" 
                    :selected="old('drive_type_id')" 
                    required 
                    placeholder="Seleccione un tipo..." />

                {{-- Marca / Modelo --}}
                <x-select 
                    name="brand_model_id" 
                    label="Marca / Modelo" 
                    :options="$brandModels->mapWithKeys(fn($m) => [$m->id => $m->brand->name . ' - ' . $m->name])" 
                    :selected="old('brand_model_id')" 
                    required 
                    placeholder="Seleccione un modelo..." />

                {{-- Capacidad Combinada (Número + Unidad de medida) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Capacidad <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2">
                            {{-- Input para el valor numérico --}}
                            <x-input 
                                type="number" 
                                name="cap_number" 
                                label="" 
                                placeholder="Ej. 512 o 1024" 
                                :value="old('cap_number')" 
                                required />
                        </div>
                        
                        <div>
                            {{-- Selector de la unidad --}}
                            <select name="cap_unit" 
                                    class="block w-full rounded-md shadow-sm text-sm px-3 py-2 border border-slate-300 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="GB" @selected(old('cap_unit') == 'GB')>GB</option>
                                <option value="TB" @selected(old('cap_unit') == 'TB')>TB</option>
                                <option value="MB" @selected(old('cap_unit') == 'MB')>MB</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('computers.show', $computer) }}"
                       class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                        Guardar disco
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection