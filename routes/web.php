<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Student Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Student Protected Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
    });
    
    // Payment Routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/', [PaymentController::class, 'show'])->name('show');
        Route::post('/initialize', [PaymentController::class, 'initialize'])->name('initialize');
        Route::get('/callback', [PaymentController::class, 'callback'])->name('callback');
    });
    
    // Application Routes
    Route::prefix('application')->name('application.')->group(function () {
        Route::get('/', [ApplicationController::class, 'show'])->name('show');
        Route::post('/', [ApplicationController::class, 'store'])->name('store');
        Route::get('/edit', [ApplicationController::class, 'edit'])->name('edit');
        Route::put('/update', [ApplicationController::class, 'update'])->name('update');
    });
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Student Management
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [AdminStudentController::class, 'index'])->name('index');
        Route::get('/{student}', [AdminStudentController::class, 'show'])->name('show');
        Route::put('/{student}/status', [AdminStudentController::class, 'updateStatus'])->name('update-status');
        
        // CSV Import
        Route::get('/import', [AdminStudentController::class, 'import'])->name('import');
        Route::post('/import', [AdminStudentController::class, 'processImport'])->name('process-import');
    });
    
    // Application Management
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [AdminApplicationController::class, 'index'])->name('index');
        Route::get('/{application}', [AdminApplicationController::class, 'show'])->name('show');
        Route::put('/{application}/review', [AdminApplicationController::class, 'review'])->name('review');
    });
});