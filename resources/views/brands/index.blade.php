@extends('layouts.app')

@section('title', 'Marcas')
@section('header', 'Marcas')

@section('content')

    {{-- 🔎 SECCIÓN DE FILTRO ÚNICO --}}
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-6">
        <form action="{{ route('brands.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
            
            {{-- BUSCAR --}}
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Buscar Marca</label>
                <input type="text" name="search" autocomplete="off" value="{{ request('search') }}" placeholder="Escribe el nombre de la marca (Ej. Dell, HP)..." class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-2 w-full sm:w-auto">
                @if(request()->filled('search'))
                    <a href="{{ route('brands.index') }}" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 text-center">
                        Limpiar
                    </a>
                @endif
                <button type="submit" class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 w-full sm:w-auto">
                    Buscar
                </button>
            </div>
            
        </form>
    </div>
@if (session('error'))
    <div class="mb-4 p-4 text-sm text-red-700 bg-red-50 rounded-lg border border-red-200 shadow-sm" role="alert">
        <span class="font-semibold">¡Atención!</span> {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="mb-4 p-4 text-sm text-green-700 bg-green-50 rounded-lg border border-green-200 shadow-sm" role="alert">
        <span class="font-semibold">Éxito:</span> {{ session('success') }}
    </div>
@endif
    {{-- 📊 HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">{{ $brands->total() }} marcas encontradas</p>
        
        <x-button-secondary :href="route('brands.create')" class="!bg-indigo-600 !text-white !ring-0 hover:!bg-indigo-500">
            + Nueva marca
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
                @forelse ($brands as $brand)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-800">
                            <a href="{{ route('brands.show', $brand) }}" class="font-medium hover:text-indigo-600">
                                {{ $brand->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-right text-sm space-x-3">
                            <a href="{{ route('brands.edit', $brand) }}" class="text-indigo-600 hover:text-indigo-800">Editar</a>
                            <form action="{{ route('brands.destroy', $brand) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta marca?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-10 text-center text-sm text-slate-400">
                            No se encontraron marcas con ese nombre.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $brands->links() }}
    </div>
@endsection