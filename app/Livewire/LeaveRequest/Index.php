<?php

namespace App\Livewire\LeaveRequest;

use App\Models\LeaveRequest;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Modal properties
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

    protected function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:sick,annual,emergency',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'status' => 'required|in:pending,approved,rejected',
            'approved_by' => 'nullable|exists:users,id',
        ];
    }

    public function store()
    {
        $this->validate();

        LeaveRequest::updateOrCreate(
            ['id' => $this->leave_request_id],
            [
                'employee_id' => $this->employee_id,
                'leave_type' => $this->leave_type,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'reason' => $this->reason,
                'status' => $this->status,
                'approved_by' => $this->approved_by,
            ]
        );

        $this->closeModal();
        $this->dispatch('leave-request-saved');
    }

    public function edit($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        $this->leave_request_id = $id;
        $this->employee_id = $leaveRequest->employee_id;
        $this->leave_type = $leaveRequest->leave_type;
        $this->start_date = $leaveRequest->start_date->format('Y-m-d');
        $this->end_date = $leaveRequest->end_date->format('Y-m-d');
        $this->reason = $leaveRequest->reason;
        $this->status = $leaveRequest->status;
        $this->approved_by = $leaveRequest->approved_by;

        $this->showLeaveRequestModal = true;
    }

    public function create()
    {
        $this->resetForm();
        $this->showLeaveRequestModal = true;
    }

    public function confirmDelete($id)
    {
        $this->leave_request_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $leaveRequest = LeaveRequest::findOrFail($this->leave_request_id);
        $leaveRequest->delete();

        $this->closeDeleteModal();
        $this->dispatch('leave-request-deleted');
    }

    public function approve($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => \Illuminate\Support\Facades\Auth::id(),
        ]);
        
        $this->dispatch('leave-request-approved');
    }

    public function reject($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => \Illuminate\Support\Facades\Auth::id(),
        ]);
        
        $this->dispatch('leave-request-rejected');
    }

    public function openModal()
    {
        $this->showLeaveRequestModal = true;
    }

    public function closeModal()
    {
        $this->showLeaveRequestModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->leave_request_id = null;
    }

    public function resetForm()
    {
        $this->leave_request_id = null;
        $this->employee_id = null;
        $this->leave_type = 'annual';
        $this->start_date = null;
        $this->end_date = null;
        $this->reason = null;
        $this->status = 'pending';
        $this->approved_by = null;
    }

    public function render()
    {
        return view('livewire.leave-request.index', [
            'leaveRequests' => LeaveRequest::with(['employee', 'approvedBy'])
                ->whereHas('employee', function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(10),
            'employees' => Employee::where('is_active', true)->get(),
        ]);
    }
}
