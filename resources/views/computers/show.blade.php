@extends('layouts.app')

@section('title', 'Detalle del equipo')
@section('header', 'Detalle de Equipo: ' . $computer->serial)

@section('content')
<div class="space-y-6">
    {{-- Botones de navegación superior --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('computers.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">
            &larr; Volver al listado
        </a>
        <div>
            <a href="{{ route('computers.edit', $computer) }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                Editar equipo
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        {{-- Bloque Izquierdo: Especificaciones Técnicas --}}
        <div class="col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Especificaciones del Sistema</h3>
                <dl class="grid grid-cols-2 gap-x-4 gap-y-4">
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Marca / Modelo</dt>
                        <dd class="text-sm font-medium text-slate-900 mt-0.5">{{ $computer->brandModel->brand->name }} — {{ $computer->brandModel->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Número de Serie</dt>
                        <dd class="text-sm font-mono font-medium text-slate-900 mt-0.5">{{ $computer->serial }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Procesador</dt>
                        <dd class="text-sm text-slate-900 mt-0.5">{{ $computer->processor ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Memoria RAM</dt>
                        <dd class="text-sm text-slate-900 mt-0.5">{{ $computer->ram ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Sistema Operativo</dt>
                        <dd class="text-sm text-slate-900 mt-0.5">{{ $computer->operatingSystem->name ?? 'Sin Sistema' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Ubicación / Departamento</dt>
                        <dd class="text-sm text-slate-900 mt-0.5">{{ $computer->department->name ?? 'No asignado' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- TABLA DE DISCOS INSTALADOS (SOLO LECTURA) --}}
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-700">Unidades de Almacenamiento Instaladas</h3>
                </div>
                @if($computer->drives->isEmpty())
                    <p class="text-sm text-slate-400 text-center py-8">Este equipo no cuenta con discos duros registrados.</p>
                @else
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Marca / Modelo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Capacidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($computer->drives as $drive)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $drive->driveType->name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $drive->brandModel->brand->name }} — {{ $drive->brandModel->name }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $drive->formatted_capacity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Bloque Derecho: Responsable e Imágenes --}}
        <div class="col-span-1 space-y-6">
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Custodio</h3>
                <p class="text-sm text-slate-900 font-medium">{{ $computer->employee ? $computer->employee->last_name . ', ' . $computer->employee->first_name : 'Disponible en Stock' }}</p>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Galería de Fotos</h3>
                @if($computer->images->isEmpty())
                    <p class="text-xs text-slate-400 text-center py-6 border border-dashed border-slate-200 rounded-md">Sin imágenes cargadas.</p>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($computer->images as $img)
                            <div class="aspect-square rounded-md overflow-hidden bg-slate-100 border border-slate-200">
                                <img src="{{ asset('storage/' . $img->path) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection