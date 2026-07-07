@extends('layouts.app')

@section('title', 'Editar departamento')
@section('header', 'Editar departamento')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('departments.update', $department) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <x-input name="name" label="Nombre del departamento" :value="$department->name" autocomplete="off" required />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar cambios</x-button>
                <x-button-secondary :href="route('departments.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection