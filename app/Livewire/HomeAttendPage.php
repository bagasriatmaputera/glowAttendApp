<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\OfficeLocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.mobile_view')]
class HomeAttendPage extends Component
{
    public $employeeId;
    public $errorMessage = '';

    public function mount()
    {
        $this->errorMessage = '';
        $user = Auth::user();
        if ($user && $user->employee) {
            $this->employeeId = $user->employee->id;
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
        }
    }

    public function clockIn($latitude, $longitude)
    {
        if (!$this->employeeId) {
            $this->errorMessage = 'Data Karyawan tidak ditemukan untuk akun ini.';
            return;
        }

        $todayAttendance = Attendance::where('employee_id', $this->employeeId)
            ->whereDate('date', Carbon::today())
            ->first();

        if ($todayAttendance) {
            $this->errorMessage = 'Anda sudah melakukan Clock In hari ini.';
            return;
        }

        $location = OfficeLocation::active()->first();
        if (!$location) {
            $this->errorMessage = 'Lokasi kantor belum diatur. Silakan hubungi Administrator.';
            return;
        }

        $distance = $location->distanceTo((float) $latitude, (float) $longitude);
        if ($distance > $location->radius) {
            $this->errorMessage = 'Anda berada di luar area kantor. Clock In ditolak.';
            return;
        }

        $status = Carbon::now()->gt($location->clock_in_time->copy()->addMinutes(15))
            ? 'late'
            : 'present';

        Attendance::create([
            'employee_id' => $this->employeeId,
            'office_location_id' => $location->id,
            'date' => Carbon::today(),
            'clock_in' => Carbon::now(),
            'latitude_in' => $latitude,
            'longitude_in' => $longitude,
            'status' => $status,
        ]);

        session()->flash('message', 'Berhasil Clock In.');
    }

    public function clockOut($latitude, $longitude)
    {
        if (!$this->employeeId) {
            $this->errorMessage = 'Data Karyawan tidak ditemukan untuk akun ini.';
            return;
        }

        $todayAttendance = Attendance::where('employee_id', $this->employeeId)
            ->whereDate('date', Carbon::today())
            ->first();

        if (!$todayAttendance) {
            $this->errorMessage = 'Anda belum melakukan Clock In hari ini.';
            return;
        }

        if ($todayAttendance->clock_out) {
            $this->errorMessage = 'Anda sudah melakukan Clock Out hari ini.';
            return;
        }

        $location = OfficeLocation::active()->first();
        if (!$location) {
            $this->errorMessage = 'Lokasi kantor belum diatur. Silakan hubungi Administrator.';
            return;
        }

        $distance = $location->distanceTo((float) $latitude, (float) $longitude);
        if ($distance > $location->radius) {
            $this->errorMessage = 'Anda berada di luar area kantor. Clock Out ditolak.';
            return;
        }

        $todayAttendance->update([
            'clock_out' => Carbon::now(),
            'latitude_out' => $latitude,
            'longitude_out' => $longitude,
        ]);

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
        $todayAttendance = null;
        $notifications = [];
        $announcements = \App\Models\Announcement::active()
            ->latest()
            ->take(3)
            ->get();

        if ($this->employeeId) {
            $todayAttendance = Attendance::where('employee_id', $this->employeeId)
                ->whereDate('date', Carbon::today())
                ->first();

            $notifications = \App\Models\Notification::where('employee_id', $this->employeeId)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        }

        return view('livewire.home-attend-page', [
            'todayAttendance' => $todayAttendance,
            'notifications' => $notifications,
            'announcements' => $announcements,
        ]);
    }
}

