@extends('layouts.app')

@section('title', 'Computadoras')
@section('header', 'Computadoras')

@section('content')

{{-- ===================== FILTROS ===================== --}}
<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 mb-6">
    <form action="{{ route('computers.index') }}" method="GET" class="space-y-4">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-6">

            {{-- BUSCAR --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Buscar</label>
                <input type="text"
                       name="search"
                       autocomplete="off"
                       value="{{ request('search') }}"
                       placeholder="Serial o empleado..."
                       class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>

            {{-- MARCA --}}
            {{-- <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Marca</label>
                <select name="brand_id" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Todas</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div> --}}

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Marca/Modelo</label>
                <select name="brand_model_id" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Todos</option>
                    @foreach($computerModels as $cModel)
                        <option value="{{ $cModel->id }}" {{ request('brand_model_id') == $cModel->id ? 'selected' : '' }}>
                            {{ $cModel->brand->name }} — {{ $cModel->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
                    RAM
                </label>

                <input type="text"
                    name="ram"
                    autocomplete="off"
                    value="{{ request('ram') }}"
                    placeholder="Ej: 8GB, 16GB"
                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>

             {{-- EMPRESA --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Empresa</label>
                <select name="company_id" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Todas</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- DEPARTAMENTO --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Departamento</label>
                <select name="department_id" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Todos</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

           

            {{-- SISTEMA OPERATIVO --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">S.O.</label>
                <select name="operating_system_id" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Todos</option>
                    @foreach($operatingSystems as $os)
                        <option value="{{ $os->id }}" {{ request('operating_system_id') == $os->id ? 'selected' : '' }}>
                            {{ $os->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ESTADO --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Estado</label>
                <select name="status" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Todos</option>
                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Asignado</option>
                    <option value="stock" {{ request('status') == 'stock' ? 'selected' : '' }}>En stock</option>
                    <option value="faulty" {{ request('status') == 'faulty' ? 'selected' : '' }}>Averiado</option>
                    <option value="obsolete" {{ request('status') == 'obsolete' ? 'selected' : '' }}>Obsoleto</option>
                </select>
            </div>

        </div>

        <div class="mt-5 border-t border-slate-200 pt-5">

    <h3 class="text-sm font-semibold text-slate-700 mb-4">
        Filtrar por disco instalado
    </h3>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-5">

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
                Tipo
            </label>

            <select name="drive_type_id"
                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">

                <option value="">Todos</option>

                @foreach($driveTypes as $type)
                    <option value="{{ $type->id }}"
                        @selected(request('drive_type_id')==$type->id)>
                        {{ $type->name }}
                    </option>
                @endforeach

            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
                Marca / Modelo
            </label>

            <select name="drive_brand_model_id"
                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">

                <option value="">Todos</option>

                @foreach($driveModels as $model)
                    <option value="{{ $model->id }}"
                        @selected(request('drive_brand_model_id')==$model->id)>

                        {{ $model->brand->name }} — {{ $model->name }}

                    </option>
                @endforeach

            </select>
        </div>

        

       <div>
    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
        Comparación
    </label>

    <select name="capacity_operator"
        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">

        <option value=">=" @selected(request('capacity_operator') == '>=')>
            Mayor o igual que
        </option>

        <option value=">" @selected(request('capacity_operator') == '>')>
            Mayor que
        </option>

        <option value="=" @selected(request('capacity_operator') == '=')>
            Igual a
        </option>

        <option value="<=" @selected(request('capacity_operator') == '<=')>
            Menor o igual que
        </option>

        <option value="<" @selected(request('capacity_operator') == '<')>
            Menor que
        </option>

    </select>
</div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
                Capacidad
            </label>

            <input type="number"
                   name="capacity_value"
                   value="{{ request('capacity_value') }}"
                   class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">
                Unidad
            </label>

            <select name="capacity_unit"
                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm">

                <option value="GB" @selected(request('capacity_unit')=='GB')>GB</option>
                <option value="TB" @selected(request('capacity_unit')=='TB')>TB</option>
                <option value="MB" @selected(request('capacity_unit')=='MB')>MB</option>

            </select>
        </div>

    </div>

</div>

        {{-- BOTONES --}}
        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
@if(request()->except('page'))
    <a href="{{ route('computers.index') }}"
       class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">
        Limpiar filtros
    </a>
@endif

            <button type="submit"
                    class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Filtrar
            </button>
        </div>

    </form>
</div>

{{-- ===================== HEADER ===================== --}}
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500">
        {{ $computers->total() }} equipos encontrados
    </p>

    <div class="flex items-center gap-2">
        {{-- 🔥 NUEVO: Botón de Exportar (Mantiene los filtros activos de la URL) --}}
    <a href="{{ route('computers.index', array_merge(request()->all(), ['export' => 'pdf'])) }}"
    class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Exportar PDF
    </a>
        <a href="{{ route('computers.create') }}"
           class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
            + Nuevo equipo
        </a>
    </div>
</div>

{{-- ===================== TABLA ===================== --}}
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">

    <table class="min-w-full divide-y divide-slate-200">

        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Serial</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Modelo</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Empleado</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Empresa</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase">Acciones</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">

            @forelse ($computers as $computer)
                <tr class="hover:bg-slate-50">

                    {{-- SERIAL --}}
                    <td class="px-6 py-4 font-mono text-sm">
                        <a href="{{ route('computers.show', $computer) }}"
                           class="text-indigo-600 hover:text-indigo-800">
                            {{ $computer->serial }}
                        </a>
                    </td>

                    {{-- MODELO --}}
                    <td class="px-6 py-4 text-sm text-slate-700">
                        {{ $computer->brandModel->brand->name }} {{ $computer->brandModel->name }}
                    </td>

                    {{-- EMPLEADO --}}
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $computer->employee
                            ? $computer->employee->first_name . ' ' . $computer->employee->last_name
                            : '—' }}
                    </td>

                    {{-- EMPRESA --}}
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $computer->company?->name ?? '—' }}
                    </td>

                    {{-- ESTADO --}}
                    <td class="px-6 py-4 text-sm">

                        @switch($computer->status)

                            @case('assigned')
                                <span class="text-green-600 font-medium">Asignado</span>
                                @break

                            @case('stock')
                                <span class="text-slate-500 font-medium">En stock</span>
                                @break

                            @case('faulty')
                                <span class="text-red-600 font-medium">Averiado</span>
                                @break

                            @case('obsolete')
                                <span class="text-amber-600 font-medium">Obsoleto</span>
                                @break

                            @default
                                <span class="text-slate-300">—</span>

                        @endswitch

                    </td>

                    {{-- ACCIONES --}}
                    <td class="px-6 py-4 text-right space-x-3 text-sm">
                        <a href="{{ route('computers.show', $computer) }}" class="text-slate-600 hover:text-indigo-600">Ver</a>
                        <a href="{{ route('computers.edit', $computer) }}" class="text-indigo-600 hover:text-indigo-800">Editar</a>

                        <form action="{{ route('computers.destroy', $computer) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('¿Eliminar equipo?')">
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
                    <td colspan="6" class="text-center py-10 text-sm text-slate-400">
                        No hay equipos registrados.
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>

{{-- ===================== PAGINACIÓN ===================== --}}
<div class="mt-4">
    {{ $computers->links() }}
</div>

@endsection