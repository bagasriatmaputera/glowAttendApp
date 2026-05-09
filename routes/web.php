<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('home', 'livewire.home-attend-page')
        ->name('home');

    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    Route::view('employees', 'employees')
        ->name('employees');

    Route::view('attendance', 'attendance')
        ->name('attendance.index');

    Route::view('leave', 'leave-requests')
        ->name('leave-requests.index');
    
    Route::view('leave-index', 'livewire.leave-request-index')
        ->name('leave-index');

    Route::get('leave-form', \App\Livewire\LeaveFormPage::class)
        ->name('leave-form-page');

    Route::view('schedules', 'schedules')
        ->name('schedules.index');

    Route::view('profile', 'profile')
        ->name('profile');
});

require __DIR__ . '/auth.php';
