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
    public $check_in;
    public $check_out;
    public $notes;
    public bool $showAttendanceModal = false;
    public bool $showDeleteModal = false;

    protected function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:present,late,absent,on_leave',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
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
                'check_in' => $this->check_in,
                'check_out' => $this->check_out,
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
        $this->check_in = $attendance->check_in;
        $this->check_out = $attendance->check_out;
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
        $this->check_in = null;
        $this->check_out = null;
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
