@extends('layouts.app')

@section('title', 'Nuevo empleado')
@section('header', 'Nuevo empleado')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">

        <form action="{{ route('employees.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <x-input name="first_name" label="Nombre" required placeholder="Ej. Juan" autocomplete="off"/>
                <x-input name="last_name" label="Apellido" required placeholder="Ej. Pérez" autocomplete="off"/>
            </div>

            <x-input
                name="employee_code"
                label="Código de empleado"
                placeholder="Ej. 101010"
                autocomplete="off"
            />
        
            <x-input
                name="email"
                label="Correo"
                placeholder="Ej. usuario@domain.com"
                autocomplete="off"
            />

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Empresa / Departamento de trabajo *</label>
                <select name="company_and_department" required 
                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white @error('company_id') border-red-500 @enderror @error('department_id') border-red-500 @enderror">
                    <option value="">Selecciona la ubicación y el área</option>
                    
                    @foreach($companies as $company)
                        <optgroup label="{{ $company->name }}">
                            @foreach($company->departments as $dept)
                                @php
                                    $combinedValue = $company->id . '-' . $dept->id;
                                @endphp
                                <option value="{{ $combinedValue }}" {{ old('company_and_department') == $combinedValue ? 'selected' : '' }}>
                                    {{ $company->name }} — {{ $dept->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('company_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
                @error('department_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <x-input
                name="extension"
                label="Extensión"
                placeholder="Sin extensión"
                autocomplete="off"
            />

            <x-select
                name="work_shift"
                label="Jornada de trabajo"
                :options="[
                    'morning/afternoon' => 'Mañana/Tarde',
                    'night' => 'Noche',
                ]"
                placeholder="Selecciona jornada"
            />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar empleado</x-button>
                <x-button-secondary :href="route('employees.index')">
                    Cancelar
                </x-button-secondary>
            </div>

        </form>
    </div>
@endsection