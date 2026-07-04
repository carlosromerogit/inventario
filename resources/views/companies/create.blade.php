@extends('layouts.app')

@section('title', 'Nueva empresa')
@section('header', 'Nueva empresa')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('companies.store') }}" method="POST" class="space-y-5">
            @csrf

            <x-input name="name" label="Nombre de la empresa" required placeholder="Ej. ACME Corp" />

            <x-input name="address" label="Dirección (opcional)" placeholder="Ej. Santo Domingo" />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar empresa</x-button>
                <x-button-secondary :href="route('companies.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection