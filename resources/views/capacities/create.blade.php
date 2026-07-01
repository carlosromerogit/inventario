@extends('layouts.app')

@section('title', 'Nueva capacidad')
@section('header', 'Nueva capacidad')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('capacities.store') }}" method="POST" class="space-y-5">
            @csrf

            <x-input name="name" label="Nombre de la capacidad" required placeholder="Ej. 512GB" />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar capacidad</x-button>
                <x-button-secondary :href="route('capacities.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection