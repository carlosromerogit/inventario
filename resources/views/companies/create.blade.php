@extends('layouts.app')

@section('title', 'Nueva empresa')
@section('header', 'Nueva empresa')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('companies.store') }}" method="POST" class="space-y-5">
            @csrf

            <x-input name="name" label="Nombre de la empresa" required placeholder="Nombre de la empresa" autocomplete="off"/>

            <x-input name="RNC" label="RNC" placeholder="Escribir RNC de la empresa" autocomplete="off"/>
            
            <x-input name="address" label="Dirección (opcional)" placeholder="Ej. Santo Domingo" autocomplete="off" />

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Departamentos que pertenecen a esta empresa</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto p-3 border border-slate-200 rounded-md bg-slate-50">
                    @forelse($departments as $dept)
                        <label class="flex items-center text-sm text-slate-600 space-x-2.5 p-1.5 rounded hover:bg-slate-100 cursor-pointer transition-colors">
                            <input type="checkbox" name="departments[]" value="{{ $dept->id }}"
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4"
                                {{ is_array(old('departments')) && in_array($dept->id, old('departments')) ? 'checked' : '' }}>
                            <span class="select-none text-slate-700 font-medium">{{ $dept->name }}</span>
                        </label>
                    @empty
                        <div class="col-span-2 p-2 text-xs text-slate-400 italic text-center">
                            No hay departamentos registrados en el sistema.
                        </div>
                    @endforelse
                </div>
                @error('departments')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar empresa</x-button>
                <x-button-secondary :href="route('companies.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection