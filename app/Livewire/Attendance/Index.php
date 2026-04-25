<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        return view('livewire.attendance.index', [
            'attendances' => Attendance::with('employee')
                ->whereHas('employee', function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%')
                      ->orWhere('employee_code', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
