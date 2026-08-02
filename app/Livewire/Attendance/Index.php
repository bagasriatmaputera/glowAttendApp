<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\Employee;
use App\Exports\AttendanceExport;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

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

    // Export properties
    public bool $showExportModal = false;
    public $exportDateFrom;
    public $exportDateTo;
    public $exportEmployeeId;

    public function mount()
    {
        $this->exportDateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->exportDateTo = Carbon::now()->format('Y-m-d');
    }

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

    protected function exportRules()
    {
        return [
            'exportDateFrom' => 'required|date',
            'exportDateTo' => 'required|date|after_or_equal:exportDateFrom',
            'exportEmployeeId' => 'nullable|exists:employees,id',
        ];
    }

    protected $messages = [
        'exportDateFrom.required' => 'Tanggal awal wajib diisi.',
        'exportDateTo.required' => 'Tanggal akhir wajib diisi.',
        'exportDateTo.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal.',
    ];

    public function store()
    {
        $this->validate();

        $isUpdate = (bool) $this->attendance_id;

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

        $this->dispatch('toast', type: 'success', message: $isUpdate
            ? 'Data kehadiran berhasil diperbarui.'
            : 'Data kehadiran berhasil ditambahkan.');

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

        $this->dispatch('toast', type: 'success', message: 'Data kehadiran berhasil dihapus.');

        $this->closeDeleteModal();
        $this->dispatch('attendance-deleted');
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

    // ========== Export ==========

    public function openExportModal()
    {
        $this->resetExportForm();
        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
        $this->resetExportForm();
    }

    public function resetExportForm()
    {
        $this->exportDateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->exportDateTo = Carbon::now()->format('Y-m-d');
        $this->exportEmployeeId = null;
        $this->resetValidation();
    }

    public function export()
    {
        $this->validate($this->exportRules());

        $filename = 'rekap-absensi-' . $this->exportDateFrom . '-sampai-' . $this->exportDateTo;
        $employeeId = $this->exportEmployeeId ?: null;

        $this->closeExportModal();
        return Excel::download(
            new AttendanceExport($this->exportDateFrom, $this->exportDateTo, $employeeId),
            $filename . '.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    public function render()
    {
        $roleName = auth()->user()->roles->first()->name ?? '';

        $query = Attendance::with('employee');

        if ($roleName === 'Employee') {
            $employeeId = auth()->user()->employee?->id;
            $query->where('employee_id', $employeeId);

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('date', 'like', '%' . $this->search . '%')
                      ->orWhere('status', 'like', '%' . $this->search . '%');
                });
            }
        } else {
            $query->whereHas('employee', function ($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('employee_code', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.attendance.index', [
            'attendances' => $query->latest()->paginate(10),
            'employees' => Employee::where('is_active', true)->get(),
        ]);
    }
}

