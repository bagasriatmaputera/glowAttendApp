<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.mobile_layout')]
class EmployeeProfilePage extends Component
{
    public $phone;
    public $address;
    public bool $showEditModal = false;

    protected function rules()
    {
        return [
            'phone' => 'required|string|max:15|min:10',
            'address' => 'required|string|max:500',
        ];
    }

    protected $messages = [
        'phone.required' => 'Nomor telepon wajib diisi.',
        'phone.max' => 'Nomor telepon maksimal 15 karakter.',
        'phone.min' => 'Nomor telepon minimal 10 karakter.',
        'address.required' => 'Alamat wajib diisi.',
        'address.max' => 'Alamat maksimal 500 karakter.',
    ];

    public function mount()
    {
        $employee = Auth::user()->employee;
        if ($employee) {
            $this->phone = $employee->phone;
            $this->address = $employee->address;
        }
    }

    public function openEditModal()
    {
        $employee = Auth::user()->employee;
        if ($employee) {
            $this->phone = $employee->phone;
            $this->address = $employee->address;
        }
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetErrorBag();
    }

    public function saveProfile()
    {
        $this->validate();

        $employee = Auth::user()->employee;
        if ($employee) {
            $employee->update([
                'phone' => $this->phone,
                'address' => $this->address,
            ]);
            session()->flash('message', 'Profil berhasil diperbarui.');
        }

        $this->showEditModal = false;
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }

    public function render()
    {
        $employee = Auth::user()->employee;
        
        return view('livewire.employee-profile-page', [
            'employee' => $employee
        ]);
    }
}
