<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DataImportController;
use App\Http\Controllers\Student\RegistrationController;
use App\Http\Controllers\Student\PaymentController;

// New Landing Page Route
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Admin Authentication Routes (No middleware protection)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['admin.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/status', [UserController::class, 'updateApplicationStatus'])->name('users.update-status');
    
    // Payment Management
    Route::post('/users/{user}/approve-payment', [UserController::class, 'approvePayment'])->name('users.approve-payment');
    Route::post('/users/{user}/reject-payment', [UserController::class, 'rejectPayment'])->name('users.reject-payment');
    
    // SMS Management
    Route::post('/users/{user}/sms', [UserController::class, 'sendSms'])->name('users.send-sms');
    Route::post('/users/bulk-sms', [UserController::class, 'bulkSms'])->name('users.bulk-sms');
    
    // Data Import & Manual Creation
    Route::get('/import', [DataImportController::class, 'index'])->name('import.index');
    Route::post('/import/upload', [DataImportController::class, 'upload'])->name('import.upload');
    Route::post('/import/create', [DataImportController::class, 'create'])->name('import.create');
});

// Student Routes
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/register', [RegistrationController::class, 'index'])->name('register');
    Route::post('/verify-phone', [RegistrationController::class, 'verifyPhone'])->name('verify-phone');
    
    Route::get('/profile', [RegistrationController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [RegistrationController::class, 'updateProfile'])->name('profile.update');
    
    Route::get('/payment', [RegistrationController::class, 'showPayment'])->name('payment');
    Route::post('/payment', [RegistrationController::class, 'processPayment'])->name('payment.process');
    
    Route::get('/status', [RegistrationController::class, 'status'])->name('status');
});

// Payment webhook
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');