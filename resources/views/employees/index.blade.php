@extends('layouts.app')

@section('title', 'Empleados')
@section('header', 'Empleados')

@section('content')

{{-- FILTROS --}}
<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-6">
    <form action="{{ route('employees.index') }}" method="GET" class="space-y-4">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">

            {{-- BUSCAR --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
                    Buscar Empleado
                </label>
                <input type="text"
                       name="search"
                       autocomplete="off"
                       value="{{ request('search') }}"
                       placeholder="Nombre o apellido..."
                       class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>

            {{-- CODIGO --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
                    Código
                </label>
                <input type="text"
                       name="employee_code"
                       value="{{ request('employee_code') }}"
                       placeholder="Ej. EMP-001"
                       autocomplete="off"
                       class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>

       

            {{-- EMPRESA --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
                    Empresa
                </label>
                <select name="company_id"
                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Todas</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

                 {{-- DEPARTAMENTO --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
                    Departamento
                </label>
                <select name="department_id"
                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Todos</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}"
                            {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- JORNADA (WORK SHIFT) --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
                    Jornada
                </label>
                <select name="work_shift"
                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Todas</option>
                    <option value="morning/afternoon" {{ request('work_shift') == 'morning/afternoon' ? 'selected' : '' }}>
                        Mañana/Tarde
                    </option>
                    {{-- <option value="afternoon" {{ request('work_shift') == 'afternoon' ? 'selected' : '' }}>
                        Tarde
                    </option> --}}
                    <option value="night" {{ request('work_shift') == 'night' ? 'selected' : '' }}>
                        Noche
                    </option>
                </select>
            </div>

        </div>

        {{-- BOTONES --}}
        <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">

            @if(request()->anyFilled(['search', 'employee_code', 'department_id', 'company_id', 'work_shift']))
                <a href="{{ route('employees.index') }}"
                   class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">
                    Limpiar
                </a>
            @endif

            <button type="submit"
                class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Filtrar
            </button>

        </div>

    </form>
</div>

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">
            {{ $employees->total() }} empleados encontrados
        </p>

        <x-button-secondary :href="route('employees.create')"
            class="!bg-indigo-600 !text-white hover:!bg-indigo-500">
            + Nuevo empleado
        </x-button-secondary>
    </div>

    {{-- TABLA --}}
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Correo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Extensión</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Empresa</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Departamento</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Equipos</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse ($employees as $employee)
                    <tr class="hover:bg-slate-50">

                        {{-- NOMBRE --}}
                        <td class="px-6 py-4">
                            <a href="{{ route('employees.show', $employee) }}"
                               class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                {{ $employee->last_name }}, {{ $employee->first_name }}
                            </a>
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $employee->employee_code ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $employee->email ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $employee->extension ?? '—' }}
                        </td>

                        {{-- EMPRESA --}}
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $employee->company?->name ?? '—' }}
                        </td>

                        {{-- DEPARTAMENTO --}}
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $employee->department?->name ?? '—' }}
                        </td>


                        {{-- EQUIPOS --}}
                        <td class="px-6 py-4 text-sm">
                            @if($employee->computers->count())
                                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 border border-green-200">
                                    {{ $employee->computers->count() }} equipo(s)
                                </span>
                            @else
                                <span class="text-slate-400">Sin equipos</span>
                            @endif
                        </td>

                        {{-- ACCIONES --}}
                        <td class="px-6 py-4 text-right space-x-3 text-sm">
                            <a href="{{ route('employees.show', $employee) }}"
                               class="text-slate-600 hover:text-indigo-600">Ver</a>

                            <a href="{{ route('employees.edit', $employee) }}"
                               class="text-indigo-600 hover:text-indigo-800">Editar</a>

                            <form action="{{ route('employees.destroy', $employee) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('¿Eliminar empleado?')">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-600 hover:text-red-800">
                                    Eliminar
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-sm text-slate-400">
                            No hay empleados registrados.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="mt-4">
        {{ $employees->links() }}
    </div>

@endsection