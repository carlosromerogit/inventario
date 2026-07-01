@extends('layouts.app')

@section('title', $driveType->name)
@section('header', $driveType->name)

@section('content')
    <div class="flex items-center justify-between mb-6">
        <x-button-secondary :href="route('drive-types.index')">← Volver al listado</x-button-secondary>
        <a href="{{ route('drive-types.edit', $driveType) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            Editar tipo de disco
        </a>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 p-6 max-w-lg">
        <dl class="space-y-4">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</dt>
                <dd class="mt-1 text-sm text-slate-800">{{ $driveType->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registrado el</dt>
                <dd class="mt-1 text-sm text-slate-800">{{ $driveType->created_at->format('d/m/Y') }}</dd>
            </div>
        </dl>
    </div>
@endsection