<?php

namespace App\Livewire\Schedule;

use App\Models\EmployeeSchedule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        return view('livewire.schedule.index', [
            'schedules' => EmployeeSchedule::with(['employee', 'schedule'])
                ->whereHas('employee', function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
