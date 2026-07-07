@extends('layouts.app')

@section('title', 'Editar sistema operativo')
@section('header', 'Editar sistema operativo')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('operating-systems.update', $operatingSystem) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <x-input name="name" label="Nombre del sistema operativo" :value="$operatingSystem->name" autocomplete="off" required />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar cambios</x-button>
                <x-button-secondary :href="route('operating-systems.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection