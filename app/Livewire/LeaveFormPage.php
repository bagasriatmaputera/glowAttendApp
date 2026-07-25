<?php

namespace App\Livewire;

use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.mobile_layout')]
class LeaveFormPage extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    
    // Modal & Form properties
    public $leave_type = 'annual';
    public $start_date;
    public $end_date;
    public $reason;
    public $attachment;
    public bool $showLeaveFormModal = false;

    public function openModal()
    {
        $this->resetForm();
        $this->showLeaveFormModal = true;
    }

    public function closeModal()
    {
        $this->showLeaveFormModal = false;
        $this->resetForm();
    }

    protected $messages = [
        'leave_type.required' => 'Jenis cuti wajib dipilih.',
        'leave_type.in' => 'Jenis cuti tidak valid.',
        'start_date.required' => 'Tanggal mulai wajib diisi.',
        'start_date.date' => 'Tanggal mulai harus berupa format tanggal.',
        'end_date.required' => 'Tanggal selesai wajib diisi.',
        'end_date.date' => 'Tanggal selesai harus berupa format tanggal.',
        'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        'reason.required' => 'Alasan cuti wajib diisi.',
        'reason.min' => 'Alasan cuti minimal harus 5 karakter.',
        'reason.max' => 'Alasan cuti maksimal 500 karakter.',
    ];

    public function resetForm()
    {
        $this->leave_type = 'annual';
        $this->start_date = null;
        $this->end_date = null;
        $this->reason = null;
        $this->attachment = null;
        $this->resetErrorBag();
    }

    public function getDurationProperty()
    {
        if ($this->start_date && $this->end_date) {
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);
            if ($end->gte($start)) {
                return $start->diffInDays($end) + 1;
            }
        }
        return 0;
    }

    public function store()
    {
        // 1. Basic Validation
        $this->validate([
            'leave_type' => 'required|in:sick,annual,emergency',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5|max:500',
        ]);

        $employee = Auth::user()->employee;
        if (!$employee) {
            session()->flash('error', 'Karyawan tidak ditemukan.');
            return;
        }

        $startDate = Carbon::parse($this->start_date)->startOfDay();
        $endDate = Carbon::parse($this->end_date)->startOfDay();
        $today = Carbon::today();

        // 2. Backdate Prevention (except for Sick Leave)
        if ($this->leave_type !== 'sick' && $startDate->lt($today)) {
            $this->addError('start_date', 'Tanggal mulai tidak boleh sebelum tanggal hari ini.');
            return;
        }

        // 3. Advance Notice for Annual Leave (min 3 days notice)
        if ($this->leave_type === 'annual' && $startDate->lt($today->copy()->addDays(3))) {
            $this->addError('start_date', 'Cuti tahunan wajib diajukan minimal 3 hari sebelum tanggal mulai.');
            return;
        }

        // 4. Overlap Prevention (check pending or approved requests)
        $overlapExists = LeaveRequest::where('employee_id', $employee->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $startDate);
                });
            })
            ->exists();

        if ($overlapExists) {
            $this->addError('start_date', 'Rentang tanggal cuti beririsan (overlap) dengan pengajuan cuti Anda sebelumnya.');
            return;
        }

        // 5. Attachment Requirement (Sick Leave & duration > 1 day)
        $duration = $this->getDurationProperty();
        if ($this->leave_type === 'sick' && $duration > 1) {
            $this->validate([
                'attachment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ], [
                'attachment.required' => 'Surat keterangan dokter wajib diunggah untuk cuti sakit lebih dari 1 hari.',
                'attachment.max' => 'Ukuran file surat keterangan dokter maksimal 2MB.',
            ]);
        }

        // Upload attachment if present
        $attachmentUrl = null;
        if ($this->attachment) {
            $path = $this->attachment->store('leave_attachments', 'public');
            $attachmentUrl = Storage::url($path);
        }

        // Save
        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type' => $this->leave_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason,
            'status' => 'pending',
            'attachment_url' => $attachmentUrl,
        ]);

        // Create notification
        \App\Models\Notification::send(
            $employee->id,
            'leave_request',
            'Pengajuan Cuti Dikirim',
            "Pengajuan cuti Anda (" . ucfirst($this->leave_type) . ") dari tanggal " . Carbon::parse($this->start_date)->format('d F Y') . " s.d " . Carbon::parse($this->end_date)->format('d F Y') . " telah diajukan."
        );

        session()->flash('message', 'Permintaan cuti berhasil diajukan.');
        $this->closeModal();
    }

    public function cancelRequest($id)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return;
        }

        $leaveRequest = LeaveRequest::where('employee_id', $employee->id)
            ->where('id', $id)
            ->first();

        if ($leaveRequest) {
            // Cancelation Eligibility check
            if ($leaveRequest->status !== 'pending') {
                session()->flash('error', 'Hanya pengajuan dengan status Pending yang dapat dibatalkan.');
                return;
            }

            // Past Dates check
            $startDate = Carbon::parse($leaveRequest->start_date)->startOfDay();
            if ($startDate->lt(Carbon::today())) {
                session()->flash('error', 'Pengajuan tidak dapat dibatalkan karena tanggal cuti sudah terlewati.');
                return;
            }

            $leaveRequest->delete();
            session()->flash('message', 'Permintaan cuti berhasil dibatalkan.');
        }
    }

    public function render()
    {
        $employee = Auth::user()->employee;
        $query = LeaveRequest::query();

        if ($employee) {
            $query->where('employee_id', $employee->id);
        } else {
            $query->whereRaw('1 = 0');
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('leave_type', 'like', '%' . $this->search . '%')
                  ->orWhere('status', 'like', '%' . $this->search . '%');
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('livewire.leave-form-page', [
            'requests' => $requests,
        ]);
    }
}
