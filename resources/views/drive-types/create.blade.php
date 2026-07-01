@extends('layouts.app')

@section('title', 'Nuevo tipo de disco')
@section('header', 'Nuevo tipo de disco')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('drive-types.store') }}" method="POST" class="space-y-5">
            @csrf

            <x-input name="name" label="Nombre del tipo de disco" required placeholder="Ej. SSD NVMe" />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar tipo de disco</x-button>
                <x-button-secondary :href="route('drive-types.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection