<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeSchedule;
use App\Models\Attendance;
use App\Models\Notification;
use Carbon\Carbon;

class CheckAbsentEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-absent-employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check employees who are on shift today but have not recorded attendance, and send a notification.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $dayOfWeek = strtolower($today->format('l')); // e.g. 'monday'
        $now = Carbon::now();

        $this->info("Checking absent employees for: {$today->format('Y-m-d')} ({$dayOfWeek}) at {$now->format('H:i:s')}");

        // Get all schedules for today
        $schedules = EmployeeSchedule::with(['employee', 'schedule'])
            ->where('day_of_week', $dayOfWeek)
            ->whereHas('employee', function($q) {
                $q->where('is_active', true);
            })
            ->get();

        $notifiedCount = 0;

        foreach ($schedules as $empSched) {
            $employee = $empSched->employee;
            $shift = $empSched->schedule;

            if (!$employee || !$shift) {
                continue;
            }

            // Check if shift start time has passed (we compare hours & minutes of today)
            $shiftStart = Carbon::today()->setTimeFromTimeString($shift->clock_in_time->format('H:i:s'));

            // Only notify if current time is past the shift start time
            if ($now->lt($shiftStart)) {
                continue;
            }

            // Check if there is already an attendance record for today
            $hasAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->exists();

            if (!$hasAttendance) {
                // To avoid sending duplicate notifications for the same day, check if one was sent today
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
            }
        }

        $this->info("Done. Sent notifications to {$notifiedCount} employees.");
    }
}
