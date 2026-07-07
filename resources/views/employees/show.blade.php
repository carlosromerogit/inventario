@extends('layouts.app')

@section('title', $employee->first_name . ' ' . $employee->last_name)
@section('header', $employee->first_name . ' ' . $employee->last_name)

@section('content')
    <div class="flex items-center justify-between mb-6">
        <x-button-secondary :href="route('employees.index')">← Volver al listado</x-button-secondary>
        <a href="{{ route('employees.edit', $employee) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            Editar empleado
        </a>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <div class="bg-white rounded-lg border border-slate-200 p-6 col-span-1">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-4">
                Información
            </h2>

            <dl class="space-y-4">

                <div>
                    <dt class="text-xs text-slate-500">Nombre completo</dt>
                    <dd class="mt-0.5 text-sm font-medium text-slate-800">
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs text-slate-500">Código de empleado</dt>
                    <dd class="mt-0.5 text-sm font-mono text-slate-800">
                        {{ $employee->employee_code ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs text-slate-500">Correo</dt>
                    <dd class="mt-0.5 text-sm font-mono text-slate-800">
                        {{ $employee->email ?? '—' }}
                    </dd>
                </div>

                

                <div>
                    <dt class="text-xs text-slate-500">Empresa</dt>
                    <dd class="mt-0.5 text-sm text-slate-800">
                        {{ $employee->company?->name ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs text-slate-500">Departamento</dt>
                    <dd class="mt-0.5 text-sm text-slate-800">
                        {{ $employee->department?->name ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-500">Extensión</dt>
                    <dd class="mt-0.5 text-sm text-slate-800">
                        {{ $employee->extension ?? '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs text-slate-500">Jornada de trabajo</dt>
                    <dd class="mt-0.5 text-sm text-slate-800">
                        @switch($employee->work_shift)
                            @case('morning/afternoon')
                                Mañana/Tarde
                                @break
                            @case('night')
                                Noche
                                @break
                            @default
                                —
                        @endswitch
                    </dd>
                </div>

                <div>
                    <dt class="text-xs text-slate-500">Registrado el</dt>
                    <dd class="mt-0.5 text-sm text-slate-800">
                        {{ $employee->created_at->format('d/m/Y') }}
                    </dd>
                </div>

            </dl>
        </div>

        <div class="col-span-2">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">
                Equipos asignados ({{ $employee->computers->count() }})
            </h2>

            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase">Serial</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase">Modelo</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase">Departamento</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($employee->computers as $computer)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 text-sm font-mono">
                                    {{ $computer->serial }}
                                </td>

                                <td class="px-5 py-3 text-sm">
                                    {{ $computer->brandModel->brand->name }} {{ $computer->brandModel->name }}
                                </td>

                                <td class="px-5 py-3 text-sm text-slate-500">
                                    {{ $computer->department?->name ?? '—' }}
                                </td>

                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('computers.show', $computer) }}"
                                       class="text-xs text-indigo-600 hover:text-indigo-800">
                                        Ver equipo →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-400">
                                    Este empleado no tiene equipos asignados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection