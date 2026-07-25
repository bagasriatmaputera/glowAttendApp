<?php

namespace App\Livewire;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.mobile_layout')]
class AttendanceHistory extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $employeeId = Auth::user()->employee?->id;

        $query = Attendance::where('employee_id', $employeeId);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('date', 'like', '%' . $this->search . '%')
                  ->orWhere('status', 'like', '%' . $this->search . '%');
            });
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(10);

        return view('livewire.attendance-history', [
            'attendances' => $attendances
        ]);
    }
}
