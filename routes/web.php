<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DataImportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Student\RegistrationController;
use App\Http\Controllers\Student\PaymentController;

// Landing Page Route
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['admin.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management - Full CRUD
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // User Status Management
    Route::patch('/users/{user}/status', [UserController::class, 'updateApplicationStatus'])->name('users.update-status');
    
    // Payment Management
    Route::post('/users/{user}/payments/{payment}/approve', [UserController::class, 'approvePayment'])->name('users.payments.approve');
    Route::post('/users/{user}/payments/{payment}/reject', [UserController::class, 'rejectPayment'])->name('users.payments.reject');
    
    // SMS Management
    Route::post('/users/{user}/sms', [UserController::class, 'sendSms'])->name('users.send-sms');
    Route::post('/users/bulk-sms', [UserController::class, 'bulkSms'])->name('users.bulk-sms');
    Route::get('/sms/settings', [UserController::class, 'smsSettings'])->name('sms.settings');
    Route::post('/sms/test', [UserController::class, 'testSms'])->name('sms.test');
    Route::get('/sms/balance', [UserController::class, 'getSmsBalance'])->name('sms.balance');
    
    // Data Import & Manual Creation
    Route::get('/import', [DataImportController::class, 'index'])->name('import.index');
    Route::post('/import/upload', [DataImportController::class, 'upload'])->name('import.upload');
    Route::post('/import/create', [DataImportController::class, 'create'])->name('import.create');
    Route::get('/import/template', [DataImportController::class, 'downloadTemplate'])->name('import.template');

    // Application Settings
    Route::get('/settings/application-deadline', [SettingController::class, 'index'])->name('settings.deadline.index');
    Route::post('/settings/application-deadline', [SettingController::class, 'store'])->name('settings.deadline.store');
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

