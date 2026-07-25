<?php

use Illuminate\Support\Facades\Route;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Schedule;
use Carbon\Carbon;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // All authenticated users
    Route::view('profile', 'profile')->name('profile');

    // Employee Routes
    Route::middleware(['role:Employee'])->group(function () {
        Route::get('home', \App\Livewire\HomeAttendPage::class)->name('home');
        Route::get('profile-karyawan', \App\Livewire\EmployeeProfilePage::class)->name('employee.profile');
        Route::get('leave-form', \App\Livewire\LeaveFormPage::class)->name('leave-form-page');
        Route::get('history', \App\Livewire\AttendanceHistory::class)->name('history');
        Route::get('notifications', \App\Livewire\NotificationPage::class)->name('notifications');
    });

    // Admin & Super Admin Routes
    Route::middleware(['role:SuperAdmin|Admin'])->group(function () {
        Route::get('dashboard', function () {
            return view('dashboard', [
                'totalEmployees' => Employee::count(),
                'presentToday' => Attendance::whereDate('date', Carbon::today())->where('status', 'present')->count(),
                'pendingLeave' => LeaveRequest::where('status', 'pending')->count(),
                'totalSchedules' => Schedule::count(),
            ]);
        })->name('dashboard');

        Route::view('employees', 'employees')->name('employees');
        Route::view('attendance', 'attendance')->name('attendance.index');
        Route::view('leave', 'leave-requests')->name('leave-requests.index');
        Route::view('leave-index', 'livewire.leave-request-index')->name('leave-index');
        Route::view('schedules', 'schedules')->name('schedules.index');
    });

});

require __DIR__ . '/auth.php';
