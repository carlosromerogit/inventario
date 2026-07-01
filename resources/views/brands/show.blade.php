@extends('layouts.app')

@section('title', $brand->name)
@section('header', $brand->name)

@section('content')
    <div class="flex items-center justify-between mb-6">
        <x-button-secondary :href="route('brands.index')">← Volver al listado</x-button-secondary>
        <a href="{{ route('brands.edit', $brand) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            Editar marca
        </a>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 p-6 max-w-lg mb-6">
        <dl class="space-y-4">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</dt>
                <dd class="mt-1 text-sm text-slate-800">{{ $brand->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registrado el</dt>
                <dd class="mt-1 text-sm text-slate-800">{{ $brand->created_at->format('d/m/Y') }}</dd>
            </div>
        </dl>
    </div>

    <div class="max-w-lg">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-slate-700">Modelos de esta marca</h2>
            <a href="{{ route('brand-models.create') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                + Agregar modelo
            </a>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 divide-y divide-slate-100">
            @forelse ($brand->brandModels as $brandModel)
                <a href="{{ route('brand-models.show', $brandModel) }}" class="block px-4 py-3 text-sm text-slate-800 hover:bg-slate-50">
                    {{ $brandModel->name }}
                </a>
            @empty
                <p class="px-4 py-6 text-center text-sm text-slate-400">Esta marca todavía no tiene modelos registrados.</p>
            @endforelse
        </div>
    </div>
@endsection