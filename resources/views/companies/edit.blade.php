@extends('layouts.app')

@section('title', 'Editar empresa')
@section('header', 'Editar empresa')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('companies.update', $company) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <x-input name="name" label="Nombre de la empresa" :value="$company->name" autocomplete="off" required />

            <x-input name="name" label="RNC de la empresa" :value="$company->RNC" autocomplete="off" required />

            <x-input name="address" label="Dirección (opcional)" :value="$company->address" autocomplete="off" />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar cambios</x-button>
                <x-button-secondary :href="route('companies.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection