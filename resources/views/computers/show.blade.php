@extends('layouts.app')

@section('title', 'Detalle del equipo')
@section('header', 'Detalle de Equipo: ' . $computer->serial)

@section('content')
<div class="space-y-6">

    {{-- HEADER ACTIONS --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('computers.index') }}"
           class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">
            ← Volver al listado
        </a>

        <a href="{{ route('computers.edit', $computer) }}"
           class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
            Editar equipo
        </a>
    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- IZQUIERDA --}}
        <div class="col-span-2 space-y-6">

            {{-- ESPECIFICACIONES --}}
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">
                    Especificaciones del Sistema
                </h3>

                <dl class="grid grid-cols-2 gap-x-4 gap-y-4">

                    <div>
                        <dt class="text-xs text-slate-400 uppercase">Marca / Modelo</dt>
                        <dd class="text-sm font-medium text-slate-900">
                            {{ $computer->brandModel?->brand?->name }}
                            — {{ $computer->brandModel?->name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-400 uppercase">Serie</dt>
                        <dd class="text-sm font-mono text-slate-900">
                            {{ $computer->serial }}
                        </dd>
                    </div>
        
                    <div>
                        <dt class="text-xs text-slate-400 uppercase">Hostname</dt>
                        <dd class="text-sm font-mono text-slate-900">
                            {{ $computer->hostname }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-400 uppercase">Procesador</dt>
                        <dd class="text-sm text-slate-900">
                            {{ $computer->processor ?? 'N/A' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-400 uppercase">RAM</dt>
                        <dd class="text-sm text-slate-900">
                            {{ $computer->ram ?? 'N/A' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-400 uppercase">Sistema Operativo</dt>
                        <dd class="text-sm text-slate-900">
                            {{ $computer->operatingSystem?->name ?? 'Sin sistema' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-400 uppercase">Departamento</dt>
                        <dd class="text-sm text-slate-900">
                            {{ $computer->department?->name ?? 'No asignado' }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-xs text-slate-400 uppercase">Activo fijo</dt>
                        <dd class="text-sm text-slate-900">
                            {{ $computer->fixed_asset ?? 'No asignado' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-400 uppercase">Empresa</dt>
                        <dd class="text-sm text-slate-900">
                            {{ $computer->company?->name ?? '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-400 uppercase">Estado</dt>
                        <dd class="text-sm font-semibold">

                            @switch($computer->status)
                                @case('assigned')
                                    <span class="text-green-600">Asignado</span>
                                    @break

                                @case('stock')
                                    <span class="text-slate-600">En stock</span>
                                    @break

                                @case('faulty')
                                    <span class="text-red-600">Averiado</span>
                                    @break

                                @case('obsolete')
                                    <span class="text-amber-600">Obsoleto</span>
                                    @break

                                @default
                                    <span class="text-slate-400">Sin estado</span>
                            @endswitch

                        </dd>
                    </div>

                </dl>
            </div>

            {{-- DISCOS --}}
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">

                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-700">
                        Unidades de almacenamiento
                    </h3>
                </div>

                @if($computer->drives->isEmpty())
                    <p class="text-sm text-slate-400 text-center py-8">
                        No hay discos registrados.
                    </p>
                @else
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Modelo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Capacidad</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach($computer->drives as $drive)
                                <tr>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $drive->driveType?->name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $drive->brandModel?->brand?->name }}
                                        — {{ $drive->brandModel?->name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm font-medium text-slate-700">
                                        {{ $drive->formatted_capacity ?? ($drive->capacity_value . ' ' . $drive->capacity_unit) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>

        {{-- DERECHA --}}
        <div class="space-y-6">

            {{-- CUSTODIO --}}
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Asignado</h3>

                <p class="text-sm text-slate-900 font-medium">
                    {{ $computer->employee
                        ? $computer->employee->last_name . ', ' . $computer->employee->first_name
                        : 'Disponible en stock'
                    }}
                </p>
            </div>

            {{-- GALERÍA --}}
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">
                    Galería
                </h3>

                @if($computer->images->isEmpty())
                    <p class="text-xs text-slate-400 text-center py-6 border border-dashed border-slate-200 rounded-md">
                        Sin imágenes
                    </p>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($computer->images as $img)
                            <div class="aspect-square rounded-md overflow-hidden bg-slate-100 border border-slate-200 cursor-pointer gallery-item"
                                 data-src="{{ asset('storage/' . $img->path) }}">
                                <img src="{{ asset('storage/' . $img->path) }}"
                                     class="w-full h-full object-cover hover:scale-105 transition">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- MODAL GALERÍA --}}
    <div id="galleryModal"
         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 p-4 opacity-0 transition">

        <button id="closeModal"
                class="absolute top-4 right-4 text-white text-2xl">
            ✕
        </button>

        <div class="max-w-4xl max-h-[85vh]">
            <img id="modalImage" class="w-full h-full object-contain">
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('galleryModal');
    const modalImg = document.getElementById('modalImage');
    const closeBtn = document.getElementById('closeModal');

    document.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('click', () => {
            modalImg.src = item.dataset.src;

            modal.classList.remove('hidden');
            setTimeout(() => modal.classList.remove('opacity-0'), 20);
        });
    });

    function closeModal() {
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });

});
</script>
@endsection