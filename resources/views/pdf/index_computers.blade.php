<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Computadoras</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #334155; margin: 10px; }
        .header { margin-bottom: 25px; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; }
        .title { font-size: 18px; font-weight: bold; color: #1e293b; }
        .date { float: right; color: #64748b; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; color: #475569; font-size: 10px; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .status { font-weight: bold; text-transform: uppercase; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <span class="date">Fecha de generación: {{ now()->format('d/m/Y H:i') }}</span>
        
        <div class="title">
            @if(count($filtrosAplicados) > 0)
                Reporte de Computadoras (Inventario Filtrado)
            @else
                Reporte General de Computadoras (Inventario Completo)
            @endif
        </div>
        
        <p>Total de equipos encontrados en este reporte: <strong>{{ $computersToExport->count() }}</strong></p>

        @if(count($filtrosAplicados) > 0)
            <div style="margin-top: 10px; padding: 8px; background-color: #f1f5f9; border-left: 4px solid #475569; font-size: 11px;">
                <strong>Criterios de filtrado aplicados:</strong>
                <span style="color: #475569;">
                    @foreach($filtrosAplicados as $criterio => $valor)
                        <strong>{{ $criterio }}:</strong> {{ $valor }} @if(!$loop->last) | @endif
                    @endforeach
                </span>
            </div>
        @endif
    </div>

    {{-- ===================== TABLA DE EQUIPOS ===================== --}}
    <table>
        <thead>
            <tr>
                <th>Serial</th>
                <th>Modelo / Marca</th>
                <th>Empleado</th>
                <th>Empresa</th>
                <th>S.O.</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($computersToExport as $computer)
                <tr>
                    <td style="font-family: monospace; font-weight: bold;">{{ $computer->serial }}</td>
                    <td>
                        {{ $computer->brandModel?->brand?->name ?? '' }} 
                        {{ $computer->brandModel?->name ?? '—' }}
                    </td>
                    <td>
                        {{ $computer->employee ? $computer->employee->first_name . ' ' . $computer->employee->last_name : '—' }}
                    </td>
                    <td>{{ $computer->company?->name ?? '—' }}</td>
                    <td>{{ $computer->operatingSystem?->name ?? '—' }}</td>
                    <td class="status">
                        @switch($computer->status)
                            @case('assigned') <span style="color: #16a34a;">Asignado</span> @break
                            @case('stock')    <span style="color: #64748b;">En Stock</span> @break
                            @case('faulty')   <span style="color: #dc2626;">Averiado</span> @break
                            @case('obsolete') <span style="color: #d97706;">Obsoleto</span> @break
                            @default          <span>{{ $computer->status }}</span>
                        @endswitch
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 20px;">
                        No se encontraron registros de equipos con los criterios seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>