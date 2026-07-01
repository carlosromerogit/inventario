@extends('layouts.app')

@section('title', 'Computadoras')
@section('header', 'Computadoras')

@section('content')
    {{-- CONTENEDOR DE FILTROS AVANZADOS --}}
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-6">
        <form action="{{ route('computers.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5">
                
                {{-- Búsqueda por Texto --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Serial o empleado..." 
                           class="block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Filtrar por Marca --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Marca</label>
                    <select name="brand_id" class="block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todas</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtrar por Departamento --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Departamento</label>
                    <select name="department_id" class="block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtrar por Sistema Operativo --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">S.O.</label>
                    <select name="operating_system_id" class="block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        @foreach($operatingSystems as $os)
                            <option value="{{ $os->id }}" {{ request('operating_system_id') == $os->id ? 'selected' : '' }}>
                                {{ $os->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtrar por Disponibilidad --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Estado</label>
                    <select name="status" class="block w-full rounded-md border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Asignados</option>
                        <option value="stock" {{ request('status') === 'stock' ? 'selected' : '' }}>En Stock (Disponibles)</option>
                    </select>
                </div>

            </div>

            {{-- Botones de acción del formulario --}}
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                @if(request()->anyFilled(['search', 'brand_id', 'department_id', 'operating_system_id', 'status']))
                    <a href="{{ route('computers.index') }}" 
                       class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">
                        Limpiar filtros
                    </a>
                @endif
                <button type="submit" 
                        class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 shadow-sm transition">
                    Aplicar Filtros
                </button>
            </div>
        </form>
    </div>

    {{-- CABECERA DE RESULTADOS --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">
            {{ $computers->total() }} {{ $computers->total() === 1 ? 'equipo encontrado' : 'equipos encontrados' }}
        </p>
        <a href="{{ route('computers.create') }}"
           class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition">
             + Nuevo equipo
        </a>
    </div>

    {{-- TABLA DE RESULTADOS --}}
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Serial</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Marca / Modelo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Empleado</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Departamento</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($computers as $computer)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 text-sm font-mono text-slate-700">
                            <a href="{{ route('computers.show', $computer) }}" class="font-medium hover:text-indigo-600">
                                {{ $computer->serial }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-800">
                            <span class="text-slate-500">{{ $computer->brandModel->brand->name }}</span>
                            {{ $computer->brandModel->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            @if($computer->employee)
                                {{ $computer->employee->first_name . ' ' . $computer->employee->last_name }}
                            @else
                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-800 border border-amber-200"> En Stock </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $computer->department?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm space-x-3 whitespace-nowrap">
                            <a href="{{ route('computers.show', $computer) }}" class="text-slate-600 hover:text-indigo-600 font-medium">Ver</a>
                            <a href="{{ route('computers.edit', $computer) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Editar</a>
                            <form action="{{ route('computers.destroy', $computer) }}" method="POST" class="inline"
                                  onsubmit="return confirm('¿Eliminar este equipo?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400">
                            No se encontraron equipos con los criterios de búsqueda seleccionados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $computers->links() }}</div>
@endsection