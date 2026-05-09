<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class LeaveFormPage extends Component
{
    public $leave_request_id;
    public $employee_id;
    public $leave_type = 'annual';
    public $start_date;
    public $end_date;
    public $reason;
    public $status = 'pending';
    public $approved_by;
    public bool $showLeaveRequestModal = false;
    public bool $showDeleteModal = false;

    public function mount($leave_request_id = null)
    {
        $this->leave_request_id = $leave_request_id;
        
        if ($leave_request_id) {
            $leaveRequest = LeaveRequest::findOrFail($leave_request_id);
            $this->employee_id = $leaveRequest->employee_id;
            $this->leave_type = $leaveRequest->leave_type;
            $this->start_date = $leaveRequest->start_date;
            $this->end_date = $leaveRequest->end_date;
            $this->reason = $leaveRequest->reason;
            $this->status = $leaveRequest->status;
            $this->approved_by = $leaveRequest->approved_by;
        }
    }

    public function store()
    {
        $validated = $this->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:sick,annual,emergency',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
            'status' => 'required|in:pending,approved,rejected',
            'approved_by' => 'nullable|exists:users,id',
        ]);

        if ($this->leave_request_id) {
            LeaveRequest::findOrFail($this->leave_request_id)->update($validated);
            session()->flash('message', 'Permintaan cuti berhasil diperbarui.');
        } else {
            LeaveRequest::create($validated);
            session()->flash('message', 'Permintaan cuti berhasil diajukan.');
        }

        return $this->redirect(route('leave-requests.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.leave-form-page', [
            'employees' => Employee::where('is_active', true)->get(),
        ]);
    }
}
