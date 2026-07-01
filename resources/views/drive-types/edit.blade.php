@extends('layouts.app')

@section('title', 'Editar tipo de disco')
@section('header', 'Editar tipo de disco')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('drive-types.update', $driveType) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <x-input name="name" label="Nombre del tipo de disco" :value="$driveType->name" required />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar cambios</x-button>
                <x-button-secondary :href="route('drive-types.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection