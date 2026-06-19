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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('departments', DepartmentController::class);
Route::resource('operating-systems', OperatingSystemController::class);
Route::resource('brands', BrandController::class);
Route::resource('brand-models', BrandModelController::class);
Route::resource('drive-types', DriveTypeController::class);
Route::resource('capacities', CapacityController::class);
Route::resource('employees', EmployeeController::class);
 
Route::resource('computers', ComputerController::class);
 
Route::resource('computers.drives', DriveController::class)
    ->shallow()
    ->except(['index', 'show']);
 
Route::post('computers/{computer}/images', [ImageController::class, 'store'])->name('computers.images.store');
Route::delete('computers/{computer}/images/{image}', [ImageController::class, 'destroy'])->name('computers.images.destroy');
 
