@extends('layouts.app')

@section('title', 'Editar empleado')
@section('header', 'Editar empleado')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('employees.update', $employee) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <x-input 
                    name="first_name" 
                    label="Nombre" 
                    :value="$employee->first_name" 
                    autocomplete="off"
                    required 
                />
                
                <x-input 
                    name="last_name" 
                    label="Apellido" 
                    :value="$employee->last_name" 
                    autocomplete="off"
                    required 
                />
            </div>

            {{-- Código de empleado --}}
            <x-input
                name="employee_code"
                label="Código de empleado"
                :value="$employee->employee_code"
                autocomplete="off"
                placeholder="Ej. EMP-001"
            />
            
            <x-input
                name="email"
                label="Correo"
                :value="$employee->email"
                autocomplete="off"
                placeholder="Ej. usuario@domain.com"
            />

            {{-- 🏭 UNIFICADO: EMPRESA / DEPARTAMENTO --}}
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
                                    // Comprobamos la opción guardada o la previa de una validación fallida
                                    $isSelected = old('company_and_department') 
                                        ? (old('company_and_department') == $combinedValue)
                                        : ($employee->company_id == $company->id && $employee->department_id == $dept->id);
                                @endphp
                                <option value="{{ $combinedValue }}" {{ $isSelected ? 'selected' : '' }}>
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
                :value="$employee->extension"
                autocomplete="off"
                placeholder="Sin extensión"
            />

    <x-select
        name="work_shift"
        label="Jornada de trabajo"
        :options="[
            'morning/afternoon' => 'Mañana/Tarde',
            'night' => 'Noche',
        ]"
        :selected="old('work_shift', $employee->work_shift)"
        placeholder="Selecciona jornada"
    />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar cambios</x-button>
                <x-button-secondary :href="route('employees.show', $employee)">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection