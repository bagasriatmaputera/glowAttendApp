<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.mobile_view')]
class EmployeeProfilePage extends Component
{
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
