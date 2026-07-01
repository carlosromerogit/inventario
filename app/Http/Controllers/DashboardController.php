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
        $totalComputers = Computer::count();
        $assignedComputers = Computer::whereNotNull('employee_id')->count();
        $stockComputers = Computer::whereNull('employee_id')->count();
        
        $totalDrives = Drive::count();
        $drivesInStock = Drive::whereHas('computer', function ($query) {
            $query->whereNull('employee_id');
        })->count();

        $departmentsData = Department::withCount('computers')
            ->orderBy('computers_count', 'desc')
            ->get();

        $osData = OperatingSystem::withCount('computers')
            ->orderBy('computers_count', 'desc')
            ->get();

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