<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandModelController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DriveController;
use App\Http\Controllers\DriveTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OperatingSystemController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('departments', DepartmentController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('operating-systems', OperatingSystemController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('brand-models', BrandModelController::class);
    Route::resource('drive-types', DriveTypeController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('computers', ComputerController::class);
    Route::resource('users', UserController::class);

    Route::get('/computers/{computer}/drives/create', [DriveController::class, 'create'])->name('computers.drives.create');
    Route::post('/computers/{computer}/drives',        [DriveController::class, 'store'])->name('computers.drives.store');
    Route::get('/drives/{drive}/edit',                 [DriveController::class, 'edit'])->name('drives.edit');
    Route::put('/drives/{drive}',                      [DriveController::class, 'update'])->name('drives.update');
    Route::delete('/drives/{drive}',                   [DriveController::class, 'destroy'])->name('drives.destroy');

    Route::post('/computers/{computer}/images',                [ImageController::class, 'store'])->name('computers.images.store');
    Route::delete('/computers/{computer}/images/{image}',      [ImageController::class, 'destroy'])->name('computers.images.destroy');

});