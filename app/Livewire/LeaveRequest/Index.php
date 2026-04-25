<?php

namespace App\Livewire\LeaveRequest;

use App\Models\LeaveRequest;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        return view('livewire.leave-request.index', [
            'leaveRequests' => LeaveRequest::with('employee')
                ->whereHas('employee', function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
