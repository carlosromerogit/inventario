@extends('layouts.app')

@section('title', 'Departamentos')
@section('header', 'Departamentos')

@section('content')

    {{-- 🔎 SECCIÓN DE FILTRO ÚNICO --}}
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-6">
        <form action="{{ route('departments.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
            
            {{-- BUSCAR --}}
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Buscar Departamento</label>
                <input type="text" name="search" autocomplete="off" value="{{ request('search') }}" placeholder="Escribe el nombre del departamento..." class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-2 w-full sm:w-auto">
                @if(request()->filled('search'))
                    <a href="{{ route('departments.index') }}" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 text-center">
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
    {{-- Mensaje de Error (Restricciones de borrado) --}}
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
    {{-- 📊 HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">{{ $departments->total() }} departamentos encontrados</p>
        
        <x-button-secondary :href="route('departments.create')" class="!bg-indigo-600 !text-white !ring-0 hover:!bg-indigo-500">
            + Nuevo departamento
        </x-button-secondary>
    </div>

    {{-- 📋 TABLA --}}
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($departments as $department)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-800">
                            <a href="{{ route('departments.show', $department) }}" class="font-medium hover:text-indigo-600">
                                {{ $department->name }}
                            </a>
                        </td>
                      
                        <td class="px-6 py-4 text-right text-sm space-x-3">
                            <a href="{{ route('departments.edit', $department) }}" class="text-indigo-600 hover:text-indigo-800">Editar</a>
                            <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este departamento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-400">
                            No se encontraron departamentos con ese nombre.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $departments->links() }}
    </div>
@endsection