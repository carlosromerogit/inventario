@extends('layouts.app')

@section('title', $capacity->name)
@section('header', $capacity->name)

@section('content')
    <div class="flex items-center justify-between mb-6">
        <x-button-secondary :href="route('capacities.index')">← Volver al listado</x-button-secondary>
        <a href="{{ route('capacities.edit', $capacity) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            Editar capacidad
        </a>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 p-6 max-w-lg">
        <dl class="space-y-4">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</dt>
                <dd class="mt-1 text-sm text-slate-800">{{ $capacity->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registrado el</dt>
                <dd class="mt-1 text-sm text-slate-800">{{ $capacity->created_at->format('d/m/Y') }}</dd>
            </div>
        </dl>
    </div>
@endsection