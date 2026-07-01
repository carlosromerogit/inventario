<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandModelController;
use App\Http\Controllers\CapacityController;
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DriveController;
use App\Http\Controllers\DriveTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OperatingSystemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// ─── Departments ─────────────────────────────────────────────────────────────
Route::get('/departments',                [DepartmentController::class, 'index']  )->name('departments.index');
Route::get('/departments/create',         [DepartmentController::class, 'create'] )->name('departments.create');
Route::post('/departments',               [DepartmentController::class, 'store']  )->name('departments.store');
Route::get('/departments/{department}',   [DepartmentController::class, 'show']   )->name('departments.show');
Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
Route::put('/departments/{department}',   [DepartmentController::class, 'update'] )->name('departments.update');
Route::delete('/departments/{department}',[DepartmentController::class, 'destroy'])->name('departments.destroy');

// ─── Operating Systems ───────────────────────────────────────────────────────
Route::get('/operating-systems',                        [OperatingSystemController::class, 'index']  )->name('operating-systems.index');
Route::get('/operating-systems/create',                 [OperatingSystemController::class, 'create'] )->name('operating-systems.create');
Route::post('/operating-systems',                       [OperatingSystemController::class, 'store']  )->name('operating-systems.store');
Route::get('/operating-systems/{operatingSystem}',      [OperatingSystemController::class, 'show']   )->name('operating-systems.show');
Route::get('/operating-systems/{operatingSystem}/edit', [OperatingSystemController::class, 'edit']   )->name('operating-systems.edit');
Route::put('/operating-systems/{operatingSystem}',      [OperatingSystemController::class, 'update'] )->name('operating-systems.update');
Route::delete('/operating-systems/{operatingSystem}',   [OperatingSystemController::class, 'destroy'])->name('operating-systems.destroy');

// ─── Brands ──────────────────────────────────────────────────────────────────
Route::get('/brands',               [BrandController::class, 'index']  )->name('brands.index');
Route::get('/brands/create',        [BrandController::class, 'create'] )->name('brands.create');
Route::post('/brands',              [BrandController::class, 'store']  )->name('brands.store');
Route::get('/brands/{brand}',       [BrandController::class, 'show']   )->name('brands.show');
Route::get('/brands/{brand}/edit',  [BrandController::class, 'edit']   )->name('brands.edit');
Route::put('/brands/{brand}',       [BrandController::class, 'update'] )->name('brands.update');
Route::delete('/brands/{brand}',    [BrandController::class, 'destroy'])->name('brands.destroy');

// ─── Brand Models ─────────────────────────────────────────────────────────────
Route::get('/brand-models',                   [BrandModelController::class, 'index']  )->name('brand-models.index');
Route::get('/brand-models/create',            [BrandModelController::class, 'create'] )->name('brand-models.create');
Route::post('/brand-models',                  [BrandModelController::class, 'store']  )->name('brand-models.store');
Route::get('/brand-models/{brandModel}',      [BrandModelController::class, 'show']   )->name('brand-models.show');
Route::get('/brand-models/{brandModel}/edit', [BrandModelController::class, 'edit']   )->name('brand-models.edit');
Route::put('/brand-models/{brandModel}',      [BrandModelController::class, 'update'] )->name('brand-models.update');
Route::delete('/brand-models/{brandModel}',   [BrandModelController::class, 'destroy'])->name('brand-models.destroy');

// ─── Drive Types ─────────────────────────────────────────────────────────────
Route::get('/drive-types',                [DriveTypeController::class, 'index']  )->name('drive-types.index');
Route::get('/drive-types/create',         [DriveTypeController::class, 'create'] )->name('drive-types.create');
Route::post('/drive-types',               [DriveTypeController::class, 'store']  )->name('drive-types.store');
Route::get('/drive-types/{driveType}',    [DriveTypeController::class, 'show']   )->name('drive-types.show');
Route::get('/drive-types/{driveType}/edit',[DriveTypeController::class, 'edit']  )->name('drive-types.edit');
Route::put('/drive-types/{driveType}',    [DriveTypeController::class, 'update'] )->name('drive-types.update');
Route::delete('/drive-types/{driveType}', [DriveTypeController::class, 'destroy'])->name('drive-types.destroy');

// ─── Capacities ──────────────────────────────────────────────────────────────
Route::get('/capacities',                [CapacityController::class, 'index']  )->name('capacities.index');
Route::get('/capacities/create',         [CapacityController::class, 'create'] )->name('capacities.create');
Route::post('/capacities',               [CapacityController::class, 'store']  )->name('capacities.store');
Route::get('/capacities/{capacity}',     [CapacityController::class, 'show']   )->name('capacities.show');
Route::get('/capacities/{capacity}/edit',[CapacityController::class, 'edit']   )->name('capacities.edit');
Route::put('/capacities/{capacity}',     [CapacityController::class, 'update'] )->name('capacities.update');
Route::delete('/capacities/{capacity}',  [CapacityController::class, 'destroy'])->name('capacities.destroy');

// ─── Employees ───────────────────────────────────────────────────────────────
Route::get('/employees',                [EmployeeController::class, 'index']  )->name('employees.index');
Route::get('/employees/create',         [EmployeeController::class, 'create'] )->name('employees.create');
Route::post('/employees',               [EmployeeController::class, 'store']  )->name('employees.store');
Route::get('/employees/{employee}',     [EmployeeController::class, 'show']   )->name('employees.show');
Route::get('/employees/{employee}/edit',[EmployeeController::class, 'edit']   )->name('employees.edit');
Route::put('/employees/{employee}',     [EmployeeController::class, 'update'] )->name('employees.update');
Route::delete('/employees/{employee}',  [EmployeeController::class, 'destroy'])->name('employees.destroy');

// ─── Computers ───────────────────────────────────────────────────────────────
Route::get('/computers',                 [ComputerController::class, 'index']  )->name('computers.index');
Route::get('/computers/create',          [ComputerController::class, 'create'] )->name('computers.create');
Route::post('/computers',                [ComputerController::class, 'store']  )->name('computers.store');
Route::get('/computers/{computer}',      [ComputerController::class, 'show']   )->name('computers.show');
Route::get('/computers/{computer}/edit', [ComputerController::class, 'edit']   )->name('computers.edit');
Route::put('/computers/{computer}',      [ComputerController::class, 'update'] )->name('computers.update');
Route::delete('/computers/{computer}',   [ComputerController::class, 'destroy'])->name('computers.destroy');

// ─── Drives (anidado bajo computer) ──────────────────────────────────────────
Route::get('/computers/{computer}/drives/create',  [DriveController::class, 'create'])->name('computers.drives.create');
Route::post('/computers/{computer}/drives',        [DriveController::class, 'store'] )->name('computers.drives.store');
Route::get('/drives/{drive}/edit',                 [DriveController::class, 'edit']  )->name('drives.edit');
Route::put('/drives/{drive}',                      [DriveController::class, 'update'])->name('drives.update');
Route::delete('/drives/{drive}',                   [DriveController::class, 'destroy'])->name('drives.destroy');

// ─── Images (anidado bajo computer) ──────────────────────────────────────────
Route::post('/computers/{computer}/images',                [ImageController::class, 'store']  )->name('computers.images.store');
Route::delete('/computers/{computer}/images/{image}',      [ImageController::class, 'destroy'])->name('computers.images.destroy');




Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');