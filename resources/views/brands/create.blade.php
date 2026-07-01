@extends('layouts.app')

@section('title', 'Nueva marca')
@section('header', 'Nueva marca')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('brands.store') }}" method="POST" class="space-y-5">
            @csrf

            <x-input name="name" label="Nombre de la marca" required placeholder="Ej. HP" />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar marca</x-button>
                <x-button-secondary :href="route('brands.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection