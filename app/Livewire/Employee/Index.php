<?php

namespace App\Livewire\Employee;

use App\Exports\EmployeeExport;
use App\Models\Employee;
use App\Models\PendingChangeRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public bool $isOwner = false;

    public function mount()
    {
        $this->isOwner = auth()->user()->isOwner();
    }

    public $employee_id;

    public $user_id;

    public $employee_code;

    public $email;

    public $username;

    public $role = 'Employee';

    public $full_name;

    public $phone;

    public $address;

    public $position;

    public $join_date;

    public $is_active = true;

    public $showEmployeeModal = false;

    public $showDeleteModal = false;

    // Export properties
    public bool $showExportModal = false;

    public $exportRole;

    public $exportStatus = '';

    protected function rules()
    {
        return [
            'employee_code' => ['required', 'string', 'max:255', Rule::unique('employees', 'employee_code')->ignore($this->employee_id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user_id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->user_id)],
            'role' => 'required|in:Owner,Management,Employee',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'position' => 'required|string|max:255',
            'join_date' => 'required|date',
            'is_active' => 'boolean',
        ];
    }

    public function create()
    {
        $this->resetInputFields();
        $this->join_date = Carbon::today()->format('Y-m-d');
        $this->generateEmployeeCode();
        $this->showEmployeeModal = true;
    }

    public function updatedJoinDate($value)
    {
        if ($value && ! $this->employee_id) {
            $this->generateEmployeeCode();
        }
    }

    public function generateEmployeeCode()
    {
        if (! $this->join_date) {
            return;
        }

        $prefix = 'EMP'.Carbon::parse($this->join_date)->format('dmy');

        $lastEmployee = Employee::withTrashed()->where('employee_code', 'like', $prefix.'%')
            ->orderBy('employee_code', 'desc')
            ->first();

        if ($lastEmployee) {
            $lastNumber = (int) substr($lastEmployee->employee_code, strlen($prefix));
            $increment = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $increment = '001';
        }

        $this->employee_code = $prefix.$increment;
    }

    private function mapRole(?string $select): string
    {
        return match ($select) {
            'Owner' => 'Management',
            'Management' => 'Admin',
            default => 'Employee',
        };
    }

    private function mapRoleToSelect(?string $roleName): string
    {
        return match ($roleName) {
            'Management' => 'Owner',
            'Admin' => 'Management',
            default => 'Employee',
        };
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $targetUser = $employee->user;

        if (! $this->isOwner && $targetUser && ($targetUser->isOwner() || $targetUser->isAdmin())) {
            $this->dispatch('toast', type: 'error', message: 'Anda tidak memiliki izin untuk mengubah akun ini.');

            return;
        }

        $this->employee_id = $id;
        $this->user_id = $employee->user_id;
        $this->email = $employee->user?->email ?? '';
        $this->username = $employee->user?->username ?? '';
        $this->role = $this->mapRoleToSelect($employee->user?->roles->first()?->name);
        $this->employee_code = $employee->employee_code;
        $this->full_name = $employee->full_name;
        $this->phone = $employee->phone;
        $this->address = $employee->address;
        $this->position = $employee->position;
        $this->join_date = $employee->join_date ? $employee->join_date->format('Y-m-d') : '';
        $this->is_active = $employee->is_active;

        $this->showEmployeeModal = true;
    }

    public function store()
    {
        if (! $this->isOwner) {
            $this->role = 'Employee';
        }

        $this->validate();

        $isUpdate = (bool) $this->employee_id;

        $targetUser = null;
        if ($isUpdate) {
            $targetUser = Employee::findOrFail($this->employee_id)->user;
        }

        // Admin tidak boleh mengubah akun dengan privilege lebih tinggi/setara
        if (! $this->isOwner && $targetUser && ($targetUser->isOwner() || $targetUser->isAdmin())) {
            $this->dispatch('toast', type: 'error', message: 'Anda tidak memiliki izin untuk mengubah akun ini.');

            return;
        }

        // Owner tidak boleh mendemote akun sendiri menjadi non-Owner
        if ($this->isOwner && $isUpdate && $targetUser && $targetUser->id === auth()->id() && $this->mapRole($this->role) !== 'Management') {
            $this->dispatch('toast', type: 'error', message: 'Anda tidak dapat mengubah role akun Anda sendiri menjadi non-Owner.');

            return;
        }

        if ($isUpdate) {
            $user = User::findOrFail($this->user_id);
            $user->update([
                'email' => $this->email,
                'username' => $this->username,
            ]);
        } else {
            $user = User::create([
                'email' => $this->email,
                'username' => $this->username,
                'password' => Hash::make('password'),
            ]);
        }

        $user->syncRoles([$this->mapRole($this->role)]);

        Employee::updateOrCreate(
            ['id' => $this->employee_id],
            [
                'user_id' => $user->id,
                'employee_code' => $this->employee_code,
                'full_name' => $this->full_name,
                'phone' => $this->phone,
                'address' => $this->address,
                'position' => $this->position,
                'join_date' => $this->join_date,
                'is_active' => $this->is_active,
            ]
        );

        $this->dispatch('toast', type: 'success', message: $isUpdate
            ? 'Data karyawan berhasil diperbarui.'
            : 'Karyawan baru berhasil ditambahkan.');

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        $targetUser = $employee->user;

        if (! $this->isOwner && $targetUser && ($targetUser->isOwner() || $targetUser->isAdmin())) {
            $this->dispatch('toast', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus akun ini.');

            return;
        }

        $this->employee_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->employee_id) {
            $employee = Employee::with('user')->findOrFail($this->employee_id);
            $targetUser = $employee->user;

            if ($this->isOwner) {
                if ($targetUser && $targetUser->id === auth()->id()) {
                    $this->dispatch('toast', type: 'error', message: 'Tidak dapat menghapus akun Anda sendiri.');
                    $this->closeDeleteModal();

                    return;
                }

                if ($targetUser && $targetUser->isOwner() && User::whereHas('roles', fn ($q) => $q->where('name', 'Management'))->count() <= 1) {
                    $this->dispatch('toast', type: 'error', message: 'Tidak dapat menghapus Owner terakhir.');
                    $this->closeDeleteModal();

                    return;
                }

                $employee->delete();
                $this->dispatch('toast', type: 'success', message: 'Karyawan berhasil dihapus.');
            } else {
                if ($targetUser && ($targetUser->isOwner() || $targetUser->isAdmin())) {
                    $this->dispatch('toast', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus akun ini.');
                    $this->closeDeleteModal();

                    return;
                }

                $this->requestDeleteEmployee($employee, $targetUser);
                $this->dispatch('toast', type: 'info', message: 'Permintaan penghapusan dikirim ke Owner untuk disetujui.');
            }
        }
        $this->closeDeleteModal();
        $this->employee_id = null;
    }

    private function requestDeleteEmployee(Employee $employee, ?User $targetUser): void
    {
        PendingChangeRequest::create([
            'type' => PendingChangeRequest::TYPE_DELETE_EMPLOYEE,
            'payload' => [
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'full_name' => $employee->full_name,
                'user_id' => $targetUser?->id,
            ],
            'requested_by' => auth()->id(),
            'status' => PendingChangeRequest::STATUS_PENDING,
        ]);
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
    }

    public function closeModal()
    {
        $this->showEmployeeModal = false;
        $this->showDeleteModal = false;
        $this->resetInputFields();
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->employee_id = null;
        $this->user_id = null;
        $this->email = '';
        $this->username = '';
        $this->role = 'Employee';
        $this->employee_code = '';
        $this->full_name = '';
        $this->phone = '';
        $this->address = '';
        $this->position = '';
        $this->join_date = '';
        $this->is_active = true;
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
        $this->exportRole = null;
        $this->exportStatus = '';
        $this->resetValidation();
    }

    public function export()
    {
        $this->validate([
            'exportRole' => 'nullable|in:Management,Admin,Employee',
            'exportStatus' => 'nullable|in:active,inactive',
        ]);

        $status = match ($this->exportStatus) {
            'active' => 1,
            'inactive' => 0,
            default => null,
        };

        $this->closeExportModal();

        return Excel::download(
            new EmployeeExport($this->exportRole, $status),
            'data-karyawan-'.now()->format('Ymd-His').'.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    public function render()
    {
        $pendingEmployeeIds = PendingChangeRequest::pending()
            ->where('type', PendingChangeRequest::TYPE_DELETE_EMPLOYEE)
            ->get()
            ->map(fn ($request) => $request->payload['employee_id'] ?? null)
            ->filter()
            ->all();

        return view('livewire.employee.index', [
            'employees' => Employee::with('user')
                ->where('full_name', 'like', '%'.$this->search.'%')
                ->orWhere('employee_code', 'like', '%'.$this->search.'%')
                ->latest()
                ->paginate(10),
            'pendingEmployeeIds' => $pendingEmployeeIds,
        ])->layout('layouts.app', [
            'header' => 'Employees',
        ]);
    }
}
