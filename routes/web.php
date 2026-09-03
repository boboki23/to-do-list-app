<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\VerifyOtpController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// ROUTE UTAMA
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// ROUTES RESET PASSWORD DENGAN OTP (GUEST)
Route::middleware(['guest'])->group(function () {
    
    // STEP 1: Form Lupa Password & Kirim OTP
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
        ->name('password.request');
    Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])
        ->name('password.send.otp');

    // STEP 2: Form Verifikasi OTP
    Route::get('/verify-otp', [VerifyOtpController::class, 'showForm'])
        ->name('password.verify');
    Route::post('/verify-otp', [VerifyOtpController::class, 'verify'])
        ->name('password.verify.otp');
    Route::post('/verify-otp/resend', [VerifyOtpController::class, 'resend'])
        ->name('password.resend.otp');

    // STEP 3: Form Reset Password
    Route::get('/reset-password', [ResetPasswordController::class, 'showForm'])
        ->name('password.reset.form');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.reset');

    // LOGIN & REGISTER (GUEST)
    
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    
    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');
});

// ROUTES AUTHENTIKASI (HANYA USER LOGIN)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Tasks Resource
    Route::resource('tasks', TaskController::class);

    // Update Status Task
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
        ->name('tasks.update-status');

    // Change Password (Hanya user yang login)
    Route::get('/change-password', [ChangePasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'change'])->name('password.change.post');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// FALLBACK ROUTE (URL TIDAK TERDAFTAR)
Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});