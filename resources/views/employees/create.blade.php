@extends('layouts.app')

@section('title', 'Nuevo empleado')
@section('header', 'Nuevo empleado')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('employees.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <x-input name="first_name" label="Nombre" required placeholder="Ej. Juan" />
                <x-input name="last_name" label="Apellido" required placeholder="Ej. Pérez" />
            </div>

            <x-select
                name="department_id"
                label="Departamento"
                :options="$departments->pluck('name', 'id')"
                placeholder="Sin departamento"
            />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar empleado</x-button>
                <x-button-secondary :href="route('employees.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection