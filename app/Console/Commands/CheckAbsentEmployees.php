<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeSchedule;
use App\Models\Attendance;
use App\Models\Notification;
use Carbon\Carbon;

class CheckAbsentEmployees extends Command
{
    protected $signature = 'app:check-absent-employees';

    protected $description = 'Check employees who are on shift today but have not recorded attendance, send notification, and mark as absent.';

    public function handle()
    {
        $today = Carbon::today();
        $dayOfWeek = strtolower($today->format('l'));
        $now = Carbon::now();

        $this->info("Checking absent employees for: {$today->format('Y-m-d')} ({$dayOfWeek}) at {$now->format('H:i:s')}");

        $schedules = EmployeeSchedule::with(['employee', 'schedule'])
            ->where('day_of_week', $dayOfWeek)
            ->whereHas('employee', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        $notifiedCount = 0;
        $absentCount = 0;

        foreach ($schedules as $empSched) {
            $employee = $empSched->employee;
            $shift = $empSched->schedule;

            if (!$employee || !$shift) {
                continue;
            }

            $shiftStart = Carbon::today()->setTimeFromTimeString($shift->clock_in_time->format('H:i:s'));

            if ($now->lt($shiftStart)) {
                continue;
            }

            $hasAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->exists();

            if (!$hasAttendance) {
                $alreadyNotified = Notification::where('employee_id', $employee->id)
                    ->where('type', 'attendance')
                    ->whereDate('created_at', $today)
                    ->exists();

                if (!$alreadyNotified) {
                    Notification::send(
                        $employee->id,
                        'attendance',
                        'Peringatan: Belum Clock In',
                        "Anda terjadwal untuk shift '{$shift->name}' hari ini ({$today->format('d-m-Y')}) mulai pukul {$shift->clock_in_time->format('H:i')}, tetapi belum melakukan Clock In."
                    );
                    $notifiedCount++;
                    $this->line("Notified employee: {$employee->full_name}");
                }

                // Mark as absent if past shift start + grace period (60 minutes)
                $graceEnd = $shiftStart->copy()->addMinutes(60);
                if ($now->gt($graceEnd)) {
                    Attendance::create([
                        'employee_id' => $employee->id,
                        'date' => $today,
                        'status' => 'absent',
                        'notes' => 'Otomatis: Tidak melakukan clock in pada shift ' . $shift->name,
                    ]);
                    $absentCount++;
                    $this->line("Marked absent: {$employee->full_name}");
                }
            }
        }

        $this->info("Done. Sent notifications to {$notifiedCount} employees, marked {$absentCount} as absent.");
    }
}

