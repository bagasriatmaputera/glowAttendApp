<?php

namespace App\Livewire\Schedule;

use App\Models\EmployeeSchedule;
use App\Models\Employee;
use App\Models\Schedule;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Modal properties
    public $schedule_id;
    public $employee_id;
    public $schedule_type_id;
    public $day_of_week = 'monday';
    public bool $showScheduleModal = false;
    public bool $showDeleteModal = false;

    protected function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'schedule_type_id' => 'required|exists:schedules,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ];
    }

    public function store()
    {
        $this->validate();

        EmployeeSchedule::updateOrCreate(
            ['id' => $this->schedule_id],
            [
                'employee_id' => $this->employee_id,
                'schedule_id' => $this->schedule_type_id,
                'day_of_week' => $this->day_of_week,
            ]
        );

        $this->closeModal();
        $this->dispatch('schedule-saved');
    }

    public function edit($id)
    {
        $schedule = EmployeeSchedule::findOrFail($id);

        $this->schedule_id = $id;
        $this->employee_id = $schedule->employee_id;
        $this->schedule_type_id = $schedule->schedule_id;
        $this->day_of_week = $schedule->day_of_week;

        $this->showScheduleModal = true;
    }

    public function create()
    {
        $this->resetForm();
        $this->showScheduleModal = true;
    }

    public function confirmDelete($id)
    {
        $this->schedule_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $schedule = EmployeeSchedule::findOrFail($this->schedule_id);
        $schedule->delete();

        $this->closeDeleteModal();
        $this->dispatch('schedule-deleted');
    }

    public function openModal()
    {
        $this->showScheduleModal = true;
    }

    public function closeModal()
    {
        $this->showScheduleModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->schedule_id = null;
    }

    public function resetForm()
    {
        $this->schedule_id = null;
        $this->employee_id = null;
        $this->schedule_type_id = null;
        $this->day_of_week = 'monday';
    }

    public function render()
    {
        return view('livewire.schedule.index', [
            'schedules' => EmployeeSchedule::with(['employee', 'schedule'])
                ->whereHas('employee', function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(10),
            'employees' => Employee::where('is_active', true)->get(),
            'scheduleTypes' => Schedule::all(),
        ]);
    }
}
