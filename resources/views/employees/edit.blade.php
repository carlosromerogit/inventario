@extends('layouts.app')

@section('title', 'Editar empleado')
@section('header', 'Editar empleado')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('employees.update', $employee) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <x-input name="first_name" label="Nombre" :value="$employee->first_name" required />
                <x-input name="last_name" label="Apellido" :value="$employee->last_name" required />
            </div>

            <x-select
                name="department_id"
                label="Departamento"
                :options="$departments->pluck('name', 'id')"
                :selected="$employee->department_id"
                placeholder="Sin departamento"
            />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar cambios</x-button>
                <x-button-secondary :href="route('employees.show', $employee)">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection