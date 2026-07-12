<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Modal properties
    public $attendance_id;
    public $employee_id;
    public $date;
    public $status = 'present';
    public $clock_in;
    public $clock_out;
    public $notes;
    public bool $showAttendanceModal = false;
    public bool $showDeleteModal = false;

    protected function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:present,late,absent,on_leave',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function store()
    {
        $this->validate();

        Attendance::updateOrCreate(
            ['id' => $this->attendance_id],
            [
                'employee_id' => $this->employee_id,
                'date' => $this->date,
                'status' => $this->status,
                'clock_in' => $this->clock_in,
                'clock_out' => $this->clock_out,
                'notes' => $this->notes,
            ]
        );

        $this->closeModal();
        $this->dispatch('attendance-saved');
    }

    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);

        $this->attendance_id = $id;
        $this->employee_id = $attendance->employee_id;
        $this->date = $attendance->date->format('Y-m-d');
        $this->status = $attendance->status;
        $this->clock_in = $attendance->clock_in ? $attendance->clock_in->format('H:i') : null;
        $this->clock_out = $attendance->clock_out ? $attendance->clock_out->format('H:i') : null;
        $this->notes = $attendance->notes;

        $this->showAttendanceModal = true;
    }

    public function create()
    {
        $this->resetForm();
        $this->showAttendanceModal = true;
    }

    public function confirmDelete($id)
    {
        $this->attendance_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $attendance = Attendance::findOrFail($this->attendance_id);
        $attendance->delete();

        $this->closeDeleteModal();
        $this->dispatch('attendance-deleted');
    }

    public function openModal()
    {
        $this->showAttendanceModal = true;
    }

    public function closeModal()
    {
        $this->showAttendanceModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->attendance_id = null;
    }

    public function resetForm()
    {
        $this->attendance_id = null;
        $this->employee_id = null;
        $this->date = null;
        $this->status = 'present';
        $this->clock_in = null;
        $this->clock_out = null;
        $this->notes = null;
    }

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
            'employees' => Employee::where('is_active', true)->get(),
        ]);
    }
}
