<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PasswordController;


// Landing page route
Route::get('/', function () {
    return view('welcome'); // Your landing page view
})->name('home');

Route::get('/', [LoginController::class, 'showLoginForm'])->name('home'); // Show login form
Route::post('/', [LoginController::class, 'handleRequest'])->name('auth.handle');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // Redirect to the login form

// Handle logout as a separate route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/user/{id}/update-role', [LoginController::class, 'updateUserRole'])->name('user.updateRole');

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Task routes
Route::get('/tasks', [TaskController::class, 'index'])->middleware('auth')->name('tasks.index'); // List all tasks
Route::get('/tasks/create', [TaskController::class, 'create'])->middleware('auth')->name('tasks.create');
Route::post('/tasks', [TaskController::class, 'store'])->middleware('auth')->name('tasks.store');

// Edit and Delete routes
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->middleware('auth')->name('tasks.edit');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->middleware('auth')->name('tasks.destroy');

Route::get('/tasks/completed', [TaskController::class, 'completed'])->name('taskcompleted');

// User Profile routes
Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');

// User Management routes
Route::middleware(['auth'])->group(function () {
    Route::get('/manage-users', [UserController::class, 'index'])->name('manage.users'); // Display users
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
});

// Password Reset and OTP Routes
Route::post('/password/send-otp', [PasswordController::class, 'sendOtp'])->name('password.sendOtp');
Route::post('/password/verify-otp', [PasswordController::class, 'verifyOtp'])->name('password.verifyOtp');
Route::post('/password/reset', [PasswordController::class, 'resetPassword'])->name('password.reset');

// Forgot Password Request Route
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Resend OTP Route
Route::get('/password/resend-otp', [PasswordController::class, 'resendOtp'])->name('password.resendOtp');
Route::post('/password/resend-otp', [PasswordController::class, 'resendOtp'])->name('password.resendOtp');

// Verify OTP Page Route
Route::get('/verify-otp', function () {
    return view('auth.verify-otp'); 
})->name('password.verifyOtp.page');

// Reset Password Page Route
Route::get('/reset-password', function () {
    return view('auth.reset-password'); // Ensure you have this view file
})->name('password.reset.page');

