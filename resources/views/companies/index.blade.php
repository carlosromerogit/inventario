@extends('layouts.app')

@section('title', 'Empresas')
@section('header', 'Empresas')

@section('content')

    {{-- 🔎 SECCIÓN DE FILTRO ÚNICO --}}
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-6">
        <form action="{{ route('companies.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
            
            {{-- BUSCAR --}}
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Buscar Empresa</label>
                <input type="text" name="search" autocomplete="off" value="{{ request('search') }}" 
                       placeholder="Buscar por nombre o RNC..." 
                       class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-2 w-full sm:w-auto">
                @if(request()->filled('search'))
                    <a href="{{ route('companies.index') }}" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 text-center">
                        Limpiar
                    </a>
                @endif
                <button type="submit" class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 w-full sm:w-auto">
                    Buscar
                </button>
            </div>
            
        </form>
    </div>

    {{-- ===================== ALERTAS DE SISTEMA ===================== --}}
    <div class="space-y-3 mb-4">
        @if (session('error'))
            <div class="flex items-center p-4 text-sm text-red-800 border border-red-200 rounded-lg bg-red-50 shadow-sm" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <div>
                    <span class="font-semibold">Operación no permitida:</span> {{ session('error') }}
                </div>
            </div>
        @endif
    </div>

    {{-- 📊 HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">{{ $companies->total() }} empresas encontradas</p>

        <x-button-secondary :href="route('companies.create')" class="!bg-indigo-600 !text-white !ring-0 hover:!bg-indigo-500">
            + Nueva empresa
        </x-button-secondary>
    </div>

    {{-- 📋 TABLA --}}
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">RNC</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Departamentos</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse ($companies as $company)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-800">
                            <a href="{{ route('companies.show', $company) }}" class="font-medium hover:text-indigo-600">
                                {{ $company->name }}
                            </a>
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-800">
                            <a href="{{ route('companies.show', $company) }}" class="font-medium hover:text-indigo-600">
                                {{ $company->RNC ?? 'N/A' }}
                            </a>
                        </td>

                        {{-- 🏬 COLUMNA DE DEPARTAMENTOS ASOCIADOS --}}
                        <td class="px-6 py-4 text-sm max-w-xs">
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($company->departments as $dept)
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 border border-slate-200">
                                        {{ $dept->name }}
                                    </span>
                                @empty
                                    <span class="text-xs italic text-slate-400">Sin departamentos</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right text-sm space-x-3 whitespace-nowrap">
                            <a href="{{ route('companies.edit', $company) }}" class="text-indigo-600 hover:text-indigo-800">
                                Editar
                            </a>

                            <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline"
                                  onsubmit="return confirm('¿Eliminar esta empresa?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- 🛠️ Corregido colspan a 4 para abarcar todas las columnas correctamente --}}
                        <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-400">
                            No se encontraron empresas con esos criterios.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $companies->links() }}
    </div>
@endsection