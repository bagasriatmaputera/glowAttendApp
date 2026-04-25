<?php

namespace App\Livewire;

use App\Models\Employee;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ModalFormEmployee extends Component
{
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
    public bool $showEmployeeModal = false;

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
        $this->join_date = $employee->join_date?->format('Y-m-d');
        $this->is_active = $employee->is_active;

        $this->showEmployeeModal = true;
    }

    public function openModal()
    {
        $this->showEmployeeModal = true;
    }

    public function closeModal()
    {
        $this->showEmployeeModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->employee_id = null;
        $this->user_id = null;
        $this->employee_code = null;
        $this->full_name = null;
        $this->phone = null;
        $this->address = null;
        $this->position = null;
        $this->join_date = null;
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.modal-form-employee');
    }
}
