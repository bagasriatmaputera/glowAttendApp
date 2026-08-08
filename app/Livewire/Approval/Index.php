<?php

namespace App\Livewire\Approval;

use App\Models\Announcement;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\PendingChangeRequest;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $statusFilter = 'pending';

    protected $queryString = ['statusFilter'];

    public function mount()
    {
        if (! auth()->user()->isOwner()) {
            abort(403);
        }
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function approve($id)
    {
        $request = PendingChangeRequest::pending()->findOrFail($id);

        $this->execute($request);

        $request->update([
            'status' => PendingChangeRequest::STATUS_APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $this->dispatch('toast', type: 'success', message: 'Permintaan disetujui dan perubahan telah dieksekusi.');
    }

    public function reject($id)
    {
        $request = PendingChangeRequest::pending()->findOrFail($id);

        $request->update([
            'status' => PendingChangeRequest::STATUS_REJECTED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $this->dispatch('toast', type: 'success', message: 'Permintaan telah ditolak.');
    }

    private function execute(PendingChangeRequest $request): void
    {
        $payload = $request->payload ?? [];

        switch ($request->type) {
            case PendingChangeRequest::TYPE_DELETE_EMPLOYEE:
                if (! empty($payload['employee_id'])) {
                    Employee::find($payload['employee_id'])?->delete();
                }
                break;

            case PendingChangeRequest::TYPE_DELETE_OFFICE_LOCATION:
                if (! empty($payload['office_location_id'])) {
                    OfficeLocation::find($payload['office_location_id'])?->delete();
                }
                break;

            case PendingChangeRequest::TYPE_DELETE_ANNOUNCEMENT:
                if (! empty($payload['announcement_id'])) {
                    Announcement::find($payload['announcement_id'])?->delete();
                }
                break;
        }
    }

    public function render()
    {
        $requests = PendingChangeRequest::with('requester')
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.approval.index', [
            'requests' => $requests,
            'pendingCount' => PendingChangeRequest::pending()->count(),
        ])->layout('layouts.app', [
            'header' => 'Persetujuan',
        ]);
    }
}
