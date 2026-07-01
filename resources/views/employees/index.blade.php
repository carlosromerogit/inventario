@extends('layouts.app')

@section('title', 'Empleados')
@section('header', 'Empleados')

@section('content')
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-6">
        <form action="{{ route('employees.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 select-none">Buscar Empleado</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nombre o apellido..." 
                           class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 select-none">Departamento</label>
                    <select name="department_id" 
                            class="block w-full rounded-md border border-slate-300 bg-white py-2 pl-3 pr-10 text-sm text-slate-800 shadow-xs transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden cursor-pointer">
                        <option value="">Todos</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 select-none">Equipos asignados</label>
                    <select name="has_computer" 
                            class="block w-full rounded-md border border-slate-300 bg-white py-2 pl-3 pr-10 text-sm text-slate-800 shadow-xs transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden cursor-pointer">
                        <option value="">Todos</option>
                        <option value="yes" {{ request('has_computer') === 'yes' ? 'selected' : '' }}>Con equipo asignado</option>
                        <option value="no" {{ request('has_computer') === 'no' ? 'selected' : '' }}>Sin equipo asignado</option>
                    </select>
                </div>

            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                @if(request()->anyFilled(['search', 'department_id', 'has_computer']))
                    <a href="{{ route('employees.index') }}" 
                       class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">
                        Limpiar filtros
                    </a>
                @endif
                <button type="submit" 
                        class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 shadow-sm transition cursor-pointer">
                    Aplicar Filtros
                </button>
            </div>
        </form>
    </div>

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">
            {{ $employees->total() }} {{ $employees->total() === 1 ? 'empleado encontrado' : 'empleados encontrados' }}
        </p>
        <x-button-secondary :href="route('employees.create')" class="!bg-indigo-600 !text-white !ring-0 hover:!bg-indigo-500 shadow-sm transition">
            + Nuevo empleado
        </x-button-secondary>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Departamento</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Equipos TI</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($employees as $employee)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('employees.show', $employee) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 transition">
                                {{ $employee->last_name }}, {{ $employee->first_name }}
                            </a>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($employee->department)
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 select-none">
                                    {{ $employee->department->name }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($employee->computers->isNotEmpty())
                                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 border border-green-200 select-none">
                                    💻 {{ $employee->computers->count() }} {{ $employee->computers->count() === 1 ? 'Equipo' : 'Equipos' }}
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-400 border border-slate-200 select-none">
                                    Sin hardware
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right text-sm space-x-3 whitespace-nowrap">
                            <a href="{{ route('employees.show', $employee) }}" class="text-slate-600 hover:text-indigo-600 font-medium transition">Ver</a>
                            <a href="{{ route('employees.edit', $employee) }}" class="text-indigo-600 hover:text-indigo-800 font-medium transition">Editar</a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este empleado?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium transition cursor-pointer">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-400">
                            No se encontraron empleados con los criterios seleccionados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>
@endsection