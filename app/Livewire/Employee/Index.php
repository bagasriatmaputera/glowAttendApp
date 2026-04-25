<?php

namespace App\Livewire\Employee;

use App\Models\Employee;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    public $employee_id;
    public $user_id;
    public $employee_code;
    public $full_name;
    public $phone;
    public $address;
    public $position;
    public $join_date;
    public $is_active = true;

    public $availableUsers = [];

    protected function rules()
    {
        return [
            'user_id' => ['required', 'exists:users,id', Rule::unique('employees', 'user_id')->ignore($this->employee_id)],
            'employee_code' => ['required', 'string', 'max:255', Rule::unique('employees', 'employee_code')->ignore($this->employee_id)],
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'position' => 'required|string|max:255',
            'join_date' => 'required|date',
            'is_active' => 'boolean',
        ];
    }

    public function mount()
    {
        $this->loadAvailableUsers();
    }

    public function loadAvailableUsers()
    {
        // Get users that don't have an employee record, or include the current employee's user
        $query = User::whereDoesntHave('employee');
        if ($this->employee_id && $this->user_id) {
            $query->orWhere('id', $this->user_id);
        }
        $this->availableUsers = $query->get();
    }

    public $showEmployeeModal = false;
    public $showDeleteModal = false;

    public function create()
    {
        $this->resetInputFields();
        $this->loadAvailableUsers();
        $this->showEmployeeModal = true;
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $this->employee_id = $id;
        $this->user_id = $employee->user_id;
        $this->employee_code = $employee->employee_code;
        $this->full_name = $employee->full_name;
        $this->phone = $employee->phone;
        $this->address = $employee->address;
        $this->position = $employee->position;
        $this->join_date = $employee->join_date ? $employee->join_date->format('Y-m-d') : '';
        $this->is_active = $employee->is_active;

        $this->loadAvailableUsers();
        $this->showEmployeeModal = true;
    }

    public function store()
    {
        $this->validate();

        Employee::updateOrCreate(
            ['id' => $this->employee_id],
            [
                'user_id' => $this->user_id,
                'employee_code' => $this->employee_code,
                'full_name' => $this->full_name,
                'phone' => $this->phone,
                'address' => $this->address,
                'position' => $this->position,
                'join_date' => $this->join_date,
                'is_active' => $this->is_active,
            ]
        );

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->employee_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->employee_id) {
            Employee::findOrFail($this->employee_id)->delete();
        }
        $this->showDeleteModal = false;
        $this->employee_id = null;
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
        $this->user_id = '';
        $this->employee_code = '';
        $this->full_name = '';
        $this->phone = '';
        $this->address = '';
        $this->position = '';
        $this->join_date = '';
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.employee.index', [
            'employees' => Employee::with('user')
                ->where('full_name', 'like', '%' . $this->search . '%')
                ->orWhere('employee_code', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10),
        ])->layout('layouts.app', [
            'header' => 'Employees',
        ]);
    }
}
