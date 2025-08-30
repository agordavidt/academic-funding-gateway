<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DataImportController;
use App\Http\Controllers\Student\RegistrationController;
use App\Http\Controllers\Student\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/status', [UserController::class, 'updateApplicationStatus'])->name('users.update-status');
    
    // Data Import
    Route::get('/import', [DataImportController::class, 'index'])->name('import.index');
    Route::post('/import/upload', [DataImportController::class, 'upload'])->name('import.upload');
});



Route::prefix('student')->name('student.')->group(function () {
    Route::get('/register', [RegistrationController::class, 'index'])->name('register');
    Route::post('/verify-phone', [RegistrationController::class, 'verifyPhone'])->name('verify-phone');
    
    Route::get('/profile', [RegistrationController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [RegistrationController::class, 'updateProfile'])->name('profile.update');
    
    Route::get('/payment', [RegistrationController::class, 'showPayment'])->name('payment');
    Route::post('/payment', [RegistrationController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/gateway/{payment}', [RegistrationController::class, 'paymentGateway'])->name('payment.gateway');
    Route::post('/payment/confirm/{payment}', [RegistrationController::class, 'confirmPayment'])->name('payment.confirm');
    
    Route::get('/status', [RegistrationController::class, 'status'])->name('status');
});

// Payment webhook (for real payment gateway integration)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Redirect root to student registration
Route::get('/', function () {
    return redirect()->route('student.register');
});