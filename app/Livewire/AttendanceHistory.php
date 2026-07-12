<?php

namespace App\Livewire;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AttendanceHistory extends Component
{
    use WithPagination;

    public function render()
    {
        $employeeId = Auth::user()->employee?->id;

        $attendances = Attendance::where('employee_id', $employeeId)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('livewire.attendance-history', [
            'attendances' => $attendances
        ]);
    }
}
