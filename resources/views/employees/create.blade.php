@extends('layouts.app')

@section('title', 'Nuevo empleado')
@section('header', 'Nuevo empleado')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">

        <form action="{{ route('employees.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- NOMBRES --}}
            <div class="grid grid-cols-2 gap-4">
                <x-input name="first_name" label="Nombre" required placeholder="Ej. Juan" autocomplete="off"/>
                <x-input name="last_name" label="Apellido" required placeholder="Ej. Pérez" autocomplete="off"/>
            </div>

            {{-- CÓDIGO EMPLEADO --}}
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

            {{-- DEPARTAMENTO --}}
            <x-select
                name="department_id"
                label="Departamento"
                :options="$departments->pluck('name', 'id')"
                placeholder="Sin departamento"
            />

            {{-- EMPRESA --}}
            <x-select
                name="company_id"
                label="Empresa"
                :options="$companies->pluck('name', 'id')"
                placeholder="Sin empresa"
            />

               <x-input
                name="extension"
                label="Extensión"
                placeholder="Sin extensión"
                autocomplete="off"
            />

            {{-- TURNO / JORNADA --}}
            <x-select
                name="work_shift"
                label="Jornada de trabajo"
                :options="[
                    'morning/afternoon' => 'Mañana/Tarde',
                    'night' => 'Noche',
                ]"
                placeholder="Selecciona jornada"
            />

            {{-- BOTONES --}}
            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar empleado</x-button>
                <x-button-secondary :href="route('employees.index')">
                    Cancelar
                </x-button-secondary>
            </div>

        </form>
    </div>
@endsection