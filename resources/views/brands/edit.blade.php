@extends('layouts.app')

@section('title', 'Editar marca')
@section('header', 'Editar marca')

@section('content')
    <div class="max-w-lg bg-white rounded-lg border border-slate-200 p-6">
        <form action="{{ route('brands.update', $brand) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <x-input name="name" label="Nombre de la marca" :value="$brand->name" autocomplete="off" required />

            <div class="flex items-center gap-3 pt-2">
                <x-button>Guardar cambios</x-button>
                <x-button-secondary :href="route('brands.index')">Cancelar</x-button-secondary>
            </div>
        </form>
    </div>
@endsection