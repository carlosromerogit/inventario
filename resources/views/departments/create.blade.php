@extends('layouts.app')

@section('title', 'Nuevo departamento')
@section('header', 'Nuevo departamento')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('departments.store') }}" method="POST" class="space-y-5">
            @csrf

            <x-input name="name" label="Nombre del departamento" required placeholder="Ej. Contabilidad" />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar departamento</x-button>
                <x-button-secondary :href="route('departments.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection