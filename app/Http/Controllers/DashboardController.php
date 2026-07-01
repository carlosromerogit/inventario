<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use App\Models\Drive;
use App\Models\Department;
use App\Models\OperatingSystem;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Métricas de Computadoras
        $totalComputers = Computer::count();
        $assignedComputers = Computer::whereNotNull('employee_id')->count();
        $stockComputers = Computer::whereNull('employee_id')->count();
        
        // 2. Métricas de Almacenamiento (Discos)
        $totalDrives = Drive::count();
        // Discos "sueltos" o en bodega (aquellos cuya PC no está asignada a nadie)
        $drivesInStock = Drive::whereHas('computer', function ($query) {
            $query->whereNull('employee_id');
        })->count();

        // 3. Datos para Gráfico: Equipos por Departamento
        $departmentsData = Department::withCount('computers')
            ->orderBy('computers_count', 'desc')
            ->get();

        // 4. Datos para Gráfico: Sistemas Operativos mas usados
        $osData = OperatingSystem::withCount('computers')
            ->orderBy('computers_count', 'desc')
            ->get();

        // 5. Tabla de actividad: Últimos 5 equipos registrados
        $recentComputers = Computer::with(['brandModel.brand', 'department'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalComputers',
            'assignedComputers',
            'stockComputers',
            'totalDrives',
            'drivesInStock',
            'departmentsData',
            'osData',
            'recentComputers'
        ));
    }
}