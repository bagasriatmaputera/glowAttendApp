<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\EmployeeSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.mobile_view')]
class HomeAttendPage extends Component
{
    public $todayAttendance;
    public $employeeId;
    public $errorMessage = '';
    public $notifications = [];
    public $todaySchedule;

    public function mount()
    {
        $this->errorMessage = '';
        $user = Auth::user();
        if ($user && $user->employee) {
            $this->employeeId = $user->employee->id;
            $this->loadTodayAttendance();
            $this->loadTodaySchedule();
            $this->loadNotifications();
        }
    }

    public function loadTodayAttendance()
    {
        if ($this->employeeId) {
            $this->todayAttendance = Attendance::where('employee_id', $this->employeeId)
                ->whereDate('date', Carbon::today())
                ->first();
        }
    }

    public function loadTodaySchedule()
    {
        if ($this->employeeId) {
            $dayOfWeek = strtolower(Carbon::today()->format('l'));
            $this->todaySchedule = EmployeeSchedule::with('schedule')
                ->where('employee_id', $this->employeeId)
                ->where('day_of_week', $dayOfWeek)
                ->first();
        }
    }

    public function loadNotifications()
    {
        if ($this->employeeId) {
            $this->notifications = \App\Models\Notification::where('employee_id', $this->employeeId)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        }
    }

    public function markAsRead($id)
    {
        $notification = \App\Models\Notification::find($id);
        if ($notification && $notification->employee_id === $this->employeeId) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
            $this->loadNotifications();
        }
    }

    public function clockIn($latitude, $longitude)
    {
        if (!$this->employeeId) {
            $this->errorMessage = 'Data Karyawan tidak ditemukan untuk akun ini.';
            return;
        }

        if ($this->todayAttendance) {
            $this->errorMessage = 'Anda sudah melakukan Clock In hari ini.';
            return;
        }

        $status = 'present';

        if ($this->todaySchedule && $this->todaySchedule->schedule) {
            $shiftStart = $this->todaySchedule->schedule->clock_in_time;
            $graceEnd = $shiftStart->copy()->addMinutes(15);

            if (Carbon::now()->gt($graceEnd)) {
                $status = 'late';
            }
        }

        Attendance::create([
            'employee_id' => $this->employeeId,
            'date' => Carbon::today(),
            'clock_in' => Carbon::now(),
            'latitude_in' => $latitude,
            'longitude_in' => $longitude,
            'status' => $status,
        ]);

        $this->loadTodayAttendance();
        session()->flash('message', 'Berhasil Clock In.');
    }

    public function clockOut($latitude, $longitude)
    {
        if (!$this->employeeId) {
            $this->errorMessage = 'Data Karyawan tidak ditemukan untuk akun ini.';
            return;
        }

        if (!$this->todayAttendance) {
            $this->errorMessage = 'Anda belum melakukan Clock In hari ini.';
            return;
        }

        if ($this->todayAttendance->clock_out) {
            $this->errorMessage = 'Anda sudah melakukan Clock Out hari ini.';
            return;
        }

        $this->todayAttendance->update([
            'clock_out' => Carbon::now(),
            'latitude_out' => $latitude,
            'longitude_out' => $longitude,
        ]);

        $this->loadTodayAttendance();
        session()->flash('message', 'Berhasil Clock Out.');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return $this->redirect('/login');
    }

    public function render()
    {
        return view('livewire.home-attend-page');
    }
}

