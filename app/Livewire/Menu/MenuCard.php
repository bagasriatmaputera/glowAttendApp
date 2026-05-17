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
            'role'  => ['Admin', 'Employee', 'SuperAdmin'],
            'route' => 'cuti.index',
            'icon'  => 'calendar',
        ],
        [
            'nama'  => 'Absen',
            'role'  => ['Admin', 'Employee', 'SuperAdmin'],
            'route' => 'absen.index',
            'icon'  => 'clock',
        ],
        [
            'nama'  => 'Karyawan',
            'role'  => ['Admin', 'SuperAdmin', 'Employee'],
            'route' => 'karyawan.index',
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
