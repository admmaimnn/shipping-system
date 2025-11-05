<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminFinancialController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/services', function () {
    return view('services');
});

Route::get('/customer', function () {
    return view('customer');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ShipmentController::class, 'index'])->name('dashboard'); 
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/financial', [AdminFinancialController::class, 'index'])->name('admin.financial');

    Route::get('/calendar', [EventController::class, 'index'])->name('calendar.index');
    Route::get('/events', [EventController::class, 'getEvents'])->name('calendar.get');
    Route::post('/events', [EventController::class, 'store'])->name('calendar.store');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('calendar.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('calendar.destroy');

    Route::resource('tasks', TaskController::class);
    Route::put('/tasks/{id}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::put('/users/{id}/role', [UserManagementController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/events', [EventController::class, 'getEvents'])->name('calendar.get');

    Route::resource('bookings', BookingController::class);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/create', [ShipmentController::class, 'create'])->name('shipments.create');
    Route::post('/shipments/create', [ShipmentController::class, 'create'])->name('shipments.extract');
    Route::post('/shipments/store', [ShipmentController::class, 'store'])->name('shipments.store');
    Route::get('/shipments/success', [ShipmentController::class, 'success'])->name('shipments.success');

    Route::get('/shipments/{id}/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
    Route::put('/shipments/{id}', [ShipmentController::class, 'update'])->name('shipments.update');
    Route::delete('/shipments/{id}', [ShipmentController::class, 'destroy'])->name('shipments.destroy');

    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.my');
});

require __DIR__.'/auth.php';
