@extends('layouts.app')

@section('title', 'Panel de Control')
@section('header', 'Dashboard de Inventario TI')

@section('content')
<div class="space-y-6">

    {{-- FILA 1: Tarjetas de Métricas Principales --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        
        {{-- Tarjeta 1: Total PCs --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-slate-200 p-5">
            <div class="flex items-center">
                <div class="p-3 rounded-md bg-indigo-50 text-indigo-600">
                    💻
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-500 truncate">Total Equipos</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ $totalComputers }}</p>
                </div>
            </div>
        </div>

        {{-- Tarjeta 2: Asignadas --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-slate-200 p-5">
            <div class="flex items-center">
                <div class="p-3 rounded-md bg-green-50 text-green-600">
                    👤
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-500 truncate">PCs Asignadas</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ $assignedComputers }}</p>
                </div>
            </div>
        </div>

        {{-- Tarjeta 3: En Stock --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-slate-200 p-5">
            <div class="flex items-center">
                <div class="p-3 rounded-md bg-amber-50 text-amber-600">
                    📦
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-500 truncate">PCs Disponibles (Stock)</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ $stockComputers }}</p>
                </div>
            </div>
        </div>

        {{-- Tarjeta 4: Discos duros --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-slate-200 p-5">
            <div class="flex items-center">
                <div class="p-3 rounded-md bg-blue-50 text-blue-600">
                    💾
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-500 truncate">Total Unidades Almacenamiento</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ $totalDrives }} <span class="text-xs text-slate-400 font-normal">({{ $drivesInStock }} en stock)</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- FILA 2: Sección de Gráficos Analíticos --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        
        {{-- Gráfico 1: Equipos por Departamento --}}
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">Equipos distribuidos por Departamento</h3>
            <div class="relative h-64">
                <canvas id="departmentsChart"></canvas>
            </div>
        </div>

        {{-- Gráfico 2: Sistemas Operativos --}}
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">Uso de Sistemas Operativos</h3>
            <div class="relative h-64">
                <canvas id="osChart"></canvas>
            </div>
        </div>
    </div>

    {{-- FILA 3: Últimas PCs registradas --}}
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-700">Últimos Equipos Incorporados al Inventario</h3>
            <a href="{{ route('computers.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900">Ver todo el listado &rarr;</a>
        </div>
        
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Serie</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Modelo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Departamento</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Ingreso</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($recentComputers as $comp)
                    <tr>
                        <td class="px-6 py-4 text-sm font-mono font-medium text-slate-900">{{ $comp->serial }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $comp->brandModel->brand->name }} — {{ $comp->brandModel->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600">
                                {{ $comp->department->name ?? 'Sin asignar' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-400">{{ $comp->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('computers.show', $comp) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400">No hay computadoras registradas en el sistema.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Script de Inicialización de Gráficos (Chart.js) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // --- Configuración Gráfico 1: Departamentos (Gráfico de Barras Horizontales) ---
    var deptsData = @json($departmentsData);
    var ctxDepts = document.getElementById('departmentsChart').getContext('2d');
    new Chart(ctxDepts, {
        type: 'bar',
        data: {
            labels: deptsData.map(d => d.name),
            datasets: [{
                label: 'Cantidad de PCs',
                data: deptsData.map(d => d.computers_count),
                backgroundColor: 'rgba(99, 102, 241, 0.8)', // Indigo
                borderColor: 'rgb(99, 102, 241)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y', // Hace las barras horizontales
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // --- Configuración Gráfico 2: Sistemas Operativos (Gráfico Tipo Dona) ---
    var osRawData = @json($osData);
    var ctxOs = document.getElementById('osChart').getContext('2d');
    new Chart(ctxOs, {
        type: 'doughnut',
        data: {
            labels: osRawData.map(o => o.name),
            datasets: [{
                data: osRawData.map(o => o.computers_count),
                backgroundColor: [
                    '#0ea5e9', // Sky
                    '#10b981', // Emerald
                    '#f59e0b', // Amber
                    '#6366f1', // Indigo
                    '#ec4899'  // Pink
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });
});
</script>
@endsection