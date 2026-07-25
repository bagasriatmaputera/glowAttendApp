<?php

namespace App\Livewire\Menu;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MenuCard extends Component
{
    public $roleUser;
    
    public $menu = [
        [
            'nama'  => 'Dashboard',
            'role'  => ['Admin', 'SuperAdmin'],
            'route' => 'dashboard',
            'icon'  => 'home',
        ],
        [
            'nama'  => 'Cuti',
            'role'  => ['Admin', 'SuperAdmin'],
            'route' => 'leave-requests.index',
            'icon'  => 'calendar',
        ],
        [
            'nama'  => 'Cuti Karyawan',
            'role'  => ['Employee'],
            'route' => 'leave-form-page',
            'icon'  => 'calendar',
        ],
        [
            'nama'  => 'Absen',
            'role'  => ['Admin', 'SuperAdmin'],
            'route' => 'attendance.index',
            'icon'  => 'clock',
        ],
        [
            'nama'  => 'Riwayat Absen',
            'role'  => ['Employee'],
            'route' => 'history',
            'icon'  => 'clock',
        ],
        [
            'nama'  => 'Karyawan',
            'role'  => ['Admin', 'SuperAdmin'],
            'route' => 'employees',
            'icon'  => 'users',
        ]
    ];
    
    public function mount()
    {   
        $this->roleUser = auth()->user()->roles->first()->name;
        // dd($this->roleUser);
    }
    
    public function render()
    {
        return view('livewire.menu.menu-card');
    }
}
