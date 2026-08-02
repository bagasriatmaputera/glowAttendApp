<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

#[Layout('layouts.mobile_layout')]
class EmployeeProfilePage extends Component
{
    public $phone;
    public $address;
    public bool $showEditModal = false;

    public $current_password;
    public $password;
    public $password_confirmation;
    public bool $showPasswordModal = false;

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

    public function openPasswordModal()
    {
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetErrorBag();
        $this->showPasswordModal = true;
    }

    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetErrorBag();
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => [
                'required', 'string', Password::defaults(), 'confirmed',
                function ($attribute, $value, $fail) {
                    if (Hash::check($value, Auth::user()->password)) {
                        $fail('Kata sandi baru tidak boleh sama dengan kata sandi lama.');
                    }
                },
            ],
        ], [
            'current_password.required' => 'Kata sandi lama wajib diisi.',
            'current_password.current_password' => 'Kata sandi lama tidak sesuai.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->dispatch('toast', type: 'success', message: 'Kata sandi berhasil diperbarui.');

        $this->closePasswordModal();
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
