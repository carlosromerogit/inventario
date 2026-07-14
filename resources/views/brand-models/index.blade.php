@extends('layouts.app')

@section('title', 'Modelos')
@section('header', 'Modelos')

@section('content')

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-6">
        <form action="{{ route('brand-models.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Buscar Modelo</label>
                    <input type="text" name="search" autocomplete="off" value="{{ request('search') }}" placeholder="Ej. Latitude 3420, ThinkPad E14..." class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Marca</label>
                    <select name="brand_id" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        <option value="">Todas</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                @if(request()->anyFilled(['search', 'brand_id']))
                    <a href="{{ route('brand-models.index') }}" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">
                        Limpiar
                    </a>
                @endif
                <button type="submit" class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Filtrar
                </button>
            </div>
        </form>
    </div>
    @if (session('error'))
    <div class="mb-4 p-4 text-sm text-red-700 bg-red-50 rounded-lg border border-red-200 shadow-sm" role="alert">
        <span class="font-semibold">¡Atención!</span> {{ session('error') }}
    </div>
@endif

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">{{ $brandModels->total() }} modelos encontrados</p>
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
                        <td class="px-6 py-4 text-sm text-slate-800 font-medium">
                            {{ $model->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-800">
                            {{ $model->brand->name }}
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
                            No se encontraron modelos con los criterios seleccionados.
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