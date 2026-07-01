@extends('layouts.app')

@section('title', 'Nuevo sistema operativo')
@section('header', 'Nuevo sistema operativo')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('operating-systems.store') }}" method="POST" class="space-y-5">
            @csrf

            <x-input name="name" label="Nombre del sistema operativo" required placeholder="Ej. Windows 11 Pro" />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar sistema operativo</x-button>
                <x-button-secondary :href="route('operating-systems.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection