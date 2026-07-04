@extends('layouts.app')

@section('title', $company->name)
@section('header', $company->name)

@section('content')
    <div class="flex items-center justify-between mb-6">
        <x-button-secondary :href="route('companies.index')">
            ← Volver al listado
        </x-button-secondary>

        <a href="{{ route('companies.edit', $company) }}"
           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            Editar empresa
        </a>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 p-6 max-w-lg">
        <dl class="space-y-4">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</dt>
                <dd class="mt-1 text-sm text-slate-800">{{ $company->name }}</dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Dirección</dt>
                <dd class="mt-1 text-sm text-slate-800">{{ $company->address ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registrada el</dt>
                <dd class="mt-1 text-sm text-slate-800">
                    {{ $company->created_at->format('d/m/Y') }}
                </dd>
            </div>
        </dl>
    </div>
@endsection