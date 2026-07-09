@extends('layouts.app')

@section('title', $department->name)
@section('header', $department->name)

@section('content')
    <div class="flex items-center justify-between mb-6">
        <x-button-secondary :href="route('departments.index')">
            ← Volver al listado
        </x-button-secondary>

        <a href="{{ route('departments.edit', $department) }}" 
           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            Editar departamento
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg border border-slate-200 p-6 md:col-span-1 h-fit">
            <h3 class="text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">Datos del Departamento</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</dt>
                    <dd class="mt-1 text-sm text-slate-800 font-medium">{{ $department->name }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registrado el</dt>
                    <dd class="mt-1 text-sm text-slate-800">
                        {{ $department->created_at->format('d/m/Y g:i A') }}
                    </dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-6 md:col-span-2">
            <h3 class="text-sm font-bold text-slate-700 mb-2 uppercase tracking-wider">Empresas que lo integran</h3>
            <p class="text-xs text-slate-400 mb-4">Lista de empresas del grupo que tienen habilitada esta área de trabajo.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse($department->companies as $company)
                    <div class="flex items-center p-3 border border-indigo-100 bg-indigo-50/30 rounded-lg shadow-sm">
                        <div class="p-2 bg-indigo-600 text-white rounded-md mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-slate-700">{{ $company->name }}</span>
                            <span class="text-xs text-slate-400">RNC: {{ $company->RNC ?? 'N/A' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 p-6 border border-dashed border-slate-200 rounded-lg text-center text-sm text-slate-400 italic">
                        Este departamento no está asignado a ninguna empresa en este momento.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection