<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('employees', 'employees')
    ->middleware(['auth', 'verified'])
    ->name('employees');

Route::view('attendance', 'attendance')
    ->middleware(['auth', 'verified'])
    ->name('attendance.index');

Route::view('leave-requests', 'leave-requests')
    ->middleware(['auth', 'verified'])
    ->name('leave-requests.index');

Route::view('schedules', 'schedules')
    ->middleware(['auth', 'verified'])
    ->name('schedules.index');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
