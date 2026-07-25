<?php

namespace App\Livewire\Schedule;

use App\Models\EmployeeSchedule;
use App\Models\Employee;
use App\Models\Schedule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $activeTab = 'types';
    public $search = '';

    // Schedule Type (Shift Definition) properties
    public $editTypeId;
    public $typeName;
    public $typeClockIn;
    public $typeClockOut;
    public bool $showTypeModal = false;
    public bool $showDeleteTypeModal = false;

    // Employee Schedule Assignment properties (existing)
    public $schedule_id;
    public $employee_id;
    public $schedule_type_id;
    public $day_of_week = 'monday';
    public bool $showScheduleModal = false;
    public bool $showDeleteModal = false;

    protected function rules()
    {
        if ($this->activeTab === 'types') {
            return [
                'typeName' => 'required|string|max:255',
                'typeClockIn' => 'required|date_format:H:i',
                'typeClockOut' => 'required|date_format:H:i|after:typeClockIn',
            ];
        }

        return [
            'employee_id' => 'required|exists:employees,id',
            'schedule_type_id' => 'required|exists:schedules,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ];
    }

    protected $messages = [
        'typeClockOut.after' => 'Jam keluar harus setelah jam masuk.',
        'typeClockIn.required' => 'Jam masuk wajib diisi.',
        'typeClockOut.required' => 'Jam keluar wajib diisi.',
        'typeName.required' => 'Nama shift wajib diisi.',
    ];

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->search = '';
    }

    // ========== Schedule Type CRUD ==========

    public function createType()
    {
        $this->resetTypeForm();
        $this->showTypeModal = true;
    }

    public function editType($id)
    {
        $type = Schedule::findOrFail($id);
        $this->editTypeId = $id;
        $this->typeName = $type->name;
        $this->typeClockIn = $type->clock_in_time->format('H:i');
        $this->typeClockOut = $type->clock_out_time->format('H:i');
        $this->showTypeModal = true;
    }

    public function storeType()
    {
        $this->validate();

        Schedule::updateOrCreate(
            ['id' => $this->editTypeId],
            [
                'name' => $this->typeName,
                'clock_in_time' => $this->typeClockIn,
                'clock_out_time' => $this->typeClockOut,
            ]
        );

        $this->closeTypeModal();
        $this->dispatch('type-saved');
    }

    public function confirmDeleteType($id)
    {
        $this->editTypeId = $id;
        $this->showDeleteTypeModal = true;
    }

    public function deleteType()
    {
        $type = Schedule::findOrFail($this->editTypeId);

        if ($type->employeeSchedules()->exists()) {
            session()->flash('error', 'Shift ini masih digunakan oleh penugasan jadwal. Hapus penugasan terlebih dahulu.');
            $this->closeDeleteTypeModal();
            return;
        }

        $type->delete();
        $this->closeDeleteTypeModal();
        $this->dispatch('type-deleted');
    }

    public function closeTypeModal()
    {
        $this->showTypeModal = false;
        $this->resetTypeForm();
    }

    public function closeDeleteTypeModal()
    {
        $this->showDeleteTypeModal = false;
        $this->editTypeId = null;
    }

    public function resetTypeForm()
    {
        $this->editTypeId = null;
        $this->typeName = '';
        $this->typeClockIn = '';
        $this->typeClockOut = '';
    }

    // ========== Employee Schedule Assignment CRUD (existing) ==========

    public function store()
    {
        $this->validate();

        $isNew = !$this->schedule_id;

        EmployeeSchedule::updateOrCreate(
            ['id' => $this->schedule_id],
            [
                'employee_id' => $this->employee_id,
                'schedule_id' => $this->schedule_type_id,
                'day_of_week' => $this->day_of_week,
            ]
        );

        $sched = Schedule::find($this->schedule_type_id);
        $timeInfo = $sched ? ' (' . \Carbon\Carbon::parse($sched->clock_in_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($sched->clock_out_time)->format('H:i') . ')' : '';
        $schedName = $sched ? $sched->name : '';

        \App\Models\Notification::send(
            $this->employee_id,
            'schedule',
            $isNew ? 'Jadwal Shift Baru Ditambahkan' : 'Jadwal Shift Diperbarui',
            $isNew
                ? 'Jadwal shift Anda untuk hari ' . ucfirst($this->day_of_week) . ' telah ditambahkan: ' . $schedName . $timeInfo . '.'
                : 'Jadwal shift Anda untuk hari ' . ucfirst($this->day_of_week) . ' telah diperbarui menjadi: ' . $schedName . $timeInfo . '.'
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
        $employeeSched = EmployeeSchedule::findOrFail($this->schedule_id);
        $employeeSched->delete();

        $this->closeDeleteModal();
        $this->dispatch('schedule-deleted');
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
        if ($this->activeTab === 'types') {
            $types = Schedule::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10);

            return view('livewire.schedule.index', [
                'scheduleTypes' => $types,
                'schedules' => collect(),
                'employees' => collect(),
                'scheduleList' => collect(),
            ]);
        }

        return view('livewire.schedule.index', [
            'schedules' => EmployeeSchedule::with(['employee', 'schedule'])
                ->whereHas('employee', function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%')
                      ->orWhere('employee_code', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(10),
            'employees' => Employee::where('is_active', true)->get(),
            'scheduleList' => Schedule::all(),
            'scheduleTypes' => collect(),
        ]);
    }
}

