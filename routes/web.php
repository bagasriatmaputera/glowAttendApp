<?php

use App\Livewire\Announcement\ListPage;
use App\Livewire\AttendanceHistory;
use App\Livewire\EmployeeProfilePage;
use App\Livewire\HomeAttendPage;
use App\Livewire\LeaveFormPage;
use App\Livewire\NotificationPage;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\PendingChangeRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {

    // All authenticated users
    Route::view('profile', 'profile')->name('profile');

    // Employee Routes
    Route::middleware(['role:Employee'])->group(function () {
        Route::get('home', HomeAttendPage::class)->name('home');
        Route::get('profile-karyawan', EmployeeProfilePage::class)->name('employee.profile');
        Route::get('leave-form', LeaveFormPage::class)->name('leave-form-page');
        Route::get('history', AttendanceHistory::class)->name('history');
        Route::get('notifications', NotificationPage::class)->name('notifications');
        Route::get('pengumuman', ListPage::class)->name('announcements.list');
    });

    // Admin & Management Routes
    Route::middleware(['role:Management|Admin'])->group(function () {
        Route::get('dashboard', function () {
            $user = auth()->user();

            return view('dashboard', [
                'totalEmployees' => Employee::count(),
                'presentToday' => Attendance::whereDate('date', Carbon::today())->where('status', 'present')->count(),
                'pendingLeave' => LeaveRequest::where('status', 'pending')->count(),
                'pendingApprovals' => $user->isOwner()
                    ? PendingChangeRequest::where('status', 'pending')->count()
                    : 0,
            ]);
        })->name('dashboard');

        Route::view('employees', 'employees')->name('employees');
        Route::view('attendance', 'attendance')->name('attendance.index');
        Route::view('leave', 'leave-requests')->name('leave-requests.index');
        Route::view('leave-index', 'livewire.leave-request-index')->name('leave-index');
        Route::view('office-locations', 'office-locations')->name('office-locations.index');
        Route::view('announcements', 'announcements')->name('announcements.index');
    });

    // Owner only routes (Management)
    Route::middleware(['role:Management'])->group(function () {
        Route::view('approvals', 'approvals')->name('approvals.index');
    });

});

require __DIR__.'/auth.php';
