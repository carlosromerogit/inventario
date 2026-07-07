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

            <x-select
                name="department_id"
                label="Departamento"
                :options="$departments->pluck('name', 'id')"
                :selected="$employee->department_id"
                placeholder="Sin departamento"
            />

            {{-- Empresa --}}
            <x-select
                name="company_id"
                label="Empresa"
                :options="$companies->pluck('name', 'id')"
                :selected="$employee->company_id"
                placeholder="Sin empresa"
            />

            <x-input
                name="extension"
                label="Extensión"
                :value="$employee->extension"
                autocomplete="off"
                placeholder="Sin extensión"
            />

            {{-- Jornada de trabajo --}}
            <x-select
                name="work_shift"
                label="Jornada de trabajo"
                :options="[
                    'morning/afternoon' => 'Mañana/Tarde',
                    // 'afternoon' => 'Tarde',
                    'night' => 'Noche',
                ]"
                :selected="$employee->work_shift"
                placeholder="Selecciona jornada"
            />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar cambios</x-button>
                <x-button-secondary :href="route('employees.show', $employee)">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection