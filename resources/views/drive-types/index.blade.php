@extends('layouts.app')

@section('title', 'Tipos de disco')
@section('header', 'Tipos de disco')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">{{ $driveTypes->total() }} tipos de disco registrados</p>
        <x-button-secondary :href="route('drive-types.create')" class="!bg-indigo-600 !text-white !ring-0 hover:!bg-indigo-500">
            + Nuevo tipo de disco
        </x-button-secondary>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($driveTypes as $driveType)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-800">
                            <a href="{{ route('drive-types.show', $driveType) }}" class="font-medium hover:text-indigo-600">
                                {{ $driveType->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-right text-sm space-x-3">
                            <a href="{{ route('drive-types.edit', $driveType) }}" class="text-indigo-600 hover:text-indigo-800">Editar</a>
                            <form action="{{ route('drive-types.destroy', $driveType) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este tipo de disco?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-10 text-center text-sm text-slate-400">
                            No hay tipos de disco registrados todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $driveTypes->links() }}
    </div>
@endsection