@extends('layouts.app')

@section('title', 'Editar departamento')
@section('header', 'Editar departamento')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('departments.update', $department) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <x-input name="name" label="Nombre del departamento" :value="$department->name" autocomplete="off" required />

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Empresas que cuentan con este departamento</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto p-3 border border-slate-200 rounded-md bg-slate-50">
                    @forelse($companies as $company)
                        @php
                            $isChecked = is_array(old('companies')) 
                                ? in_array($company->id, old('companies')) 
                                : $department->companies->contains($company->id);
                        @endphp
                        <label class="flex items-center text-sm text-slate-600 space-x-2.5 p-1.5 rounded hover:bg-slate-100 cursor-pointer transition-colors">
                            <input type="checkbox" name="companies[]" value="{{ $company->id }}"
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4"
                                {{ $isChecked ? 'checked' : '' }}>
                            <span class="select-none text-slate-700 font-medium">{{ $company->name }}</span>
                        </label>
                    @empty
                        <div class="col-span-2 p-2 text-xs text-slate-400 italic text-center">
                            No hay empresas registradas en el sistema todavía.
                        </div>
                    @endforelse
                </div>
                @error('companies')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar cambios</x-button>
                <x-button-secondary :href="route('departments.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection