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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- 📊 INFORMACIÓN GENERAL --}}
        <div class="bg-white rounded-lg border border-slate-200 p-6 md:col-span-1 h-fit">
            <h3 class="text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">Datos de la Empresa</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $company->name }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">RNC</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $company->RNC ?? '—' }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Dirección</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $company->address ?? '—' }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registrada el</dt>
                    <dd class="mt-1 text-sm text-slate-800">
                        {{ $company->created_at->format('d/m/Y g:i A') }}
                    </dd>
                </div>
            </dl>
        </div>

        {{-- 🏬 DEPARTAMENTOS ASOCIADOS --}}
        <div class="bg-white rounded-lg border border-slate-200 p-6 md:col-span-2">
            <h3 class="text-sm font-bold text-slate-700 mb-2 uppercase tracking-wider">Departamentos Habilitados</h3>
            <p class="text-xs text-slate-400 mb-4">Áreas operativas asociadas a esta sucursal o razón social.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse($company->departments as $dept)
                    <div class="flex items-center p-3 border border-slate-100 bg-slate-50 rounded-lg shadow-sm">
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-md mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700">{{ $dept->name }}</span>
                    </div>
                @empty
                    <div class="col-span-2 p-6 border border-dashed border-slate-200 rounded-lg text-center text-sm text-slate-400 italic">
                        Esta empresa no tiene departamentos configurados todavía.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection