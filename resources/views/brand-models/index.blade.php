@extends('layouts.app')

@section('title', 'Modelos')
@section('header', 'Modelos')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">{{ $brandModels->total() }} modelos registrados</p>
        <x-button-secondary :href="route('brand-models.create')" class="!bg-indigo-600 !text-white !ring-0 hover:!bg-indigo-500">
            + Nuevo modelo
        </x-button-secondary>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Marca</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Modelo</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($brandModels as $model)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-800">
                            {{ $model->brand->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-800 font-medium">
                            {{ $model->name }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm space-x-3">
                            <a href="{{ route('brand-models.edit', $model) }}" class="text-indigo-600 hover:text-indigo-800">Editar</a>
                            <form action="{{ route('brand-models.destroy', $model) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este modelo?');">
                                @csrf
                                @method('DELETE') 
                                <button type="submit" class="text-red-600 hover:text-red-800">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-400">
                            No hay modelos registrados todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $brandModels->links() }}
    </div>
@endsection