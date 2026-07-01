@extends('layouts.app')

@section('title', $operatingSystem->name)
@section('header', $operatingSystem->name)

@section('content')
    <div class="flex items-center justify-between mb-6">
        <x-button-secondary :href="route('operating-systems.index')">← Volver al listado</x-button-secondary>
        <a href="{{ route('operating-systems.edit', $operatingSystem) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            Editar sistema operativo
        </a>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 p-6 max-w-lg">
        <dl class="space-y-4">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</dt>
                <dd class="mt-1 text-sm text-slate-800">{{ $operatingSystem->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registrado el</dt>
                <dd class="mt-1 text-sm text-slate-800">{{ $operatingSystem->created_at->format('d/m/Y') }}</dd>
            </div>
        </dl>
    </div>
@endsection