@extends('layouts.app')

@section('title', 'Modelos')
@section('header', 'Modelos')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
        <h2 class="text-lg font-medium">Listado de modelos</h2>

        <a href="{{ route('brand-models.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            Nuevo modelo
        </a>
    </div>

    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">
                    Marca
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">
                    Modelo
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">
                    Acciones
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-200 bg-white">
            @forelse ($brandModels as $model)
                <tr>
                    <td class="px-6 py-4">
                        {{ $model->brand->name }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $model->name }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('brand-models.edit', $model) }}"
                               class="px-3 py-1.5 text-sm bg-amber-500 text-white rounded hover:bg-amber-600">
                                Editar
                            </a>

                            <form action="{{ route('brand-models.destroy', $model) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Eliminar este modelo?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="px-3 py-1.5 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                        No hay modelos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-6 py-4 border-t border-slate-200">
        {{ $brandModels->links() }}
    </div>
</div>
@endsection