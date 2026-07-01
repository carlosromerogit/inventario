@extends('layouts.app')

@section('title', 'Editar disco')
@section('header', 'Editar disco de hardware')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">

        {{-- El action apunta de forma directa al ID del recurso de almacenamiento --}}
        <form action="{{ route('drives.update', $drive) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                {{-- Tipo de Unidad --}}
                <x-select 
                    name="drive_type_id" 
                    label="Tipo de disco" 
                    :options="$driveTypes->pluck('name', 'id')" 
                    :selected="old('drive_type_id', $drive->drive_type_id)" 
                    required />

                {{-- Marca / Modelo de Almacenamiento --}}
                <x-select 
                    name="brand_model_id" 
                    label="Marca / Modelo" 
                    :options="$brandModels->mapWithKeys(fn($m) => [$m->id => $m->brand->name . ' — ' . $m->name])" 
                    :selected="old('brand_model_id', $drive->brand_model_id)" 
                    required />

                {{-- Segmento de Capacidad Desacoplado --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Capacidad <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="grid grid-cols-3 gap-3">
                        {{-- Input numérico nativo --}}
                        <div class="col-span-2">
                            <x-input 
                                type="number" 
                                name="cap_number" 
                                label="" 
                                placeholder="Ej. 512" 
                                :value="old('cap_number', $currentNumber)" 
                                required />
                        </div>
                        
                        {{-- Selector de unidades estáticas --}}
                        <div>
                            <select name="cap_unit" 
                                    class="block w-full rounded-md shadow-sm text-sm px-3 py-2 border border-slate-300 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="GB" @selected(old('cap_unit', $currentUnit) == 'GB')>GB</option>
                                <option value="TB" @selected(old('cap_unit', $currentUnit) == 'TB')>TB</option>
                                <option value="MB" @selected(old('cap_unit', $currentUnit) == 'MB')>MB</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Botones de Control inferior --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-slate-100">
                    <a href="{{ route('computers.show', $computer) }}"
                       class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                        Actualizar disco
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection