<?php

namespace App\Livewire\Navigation;

use Livewire\Component;

class NavBottom extends Component
{
    public $menu = [
        [
            'nama' => 'Home',
            'route' => 'home',
            'icon' => 'home',
        ],
        [
            'nama' => 'Employees',
            'route' => 'employees',
            'icon' => 'users',
        ],
        [
            'nama' => 'Requests',
            'route' => 'leave-form-page',
            'icon' => 'document',
        ],
        [
            'nama' => 'Inbox',
            'route' => 'inbox',
            'icon' => 'bell',
        ],
        [
            'nama' => 'Profile',
            'route' => 'employee.profile',
            'icon' => 'user',
        ],
    ];
    
    public function render()
    {
        return view('livewire.navigation.nav-bottom');
    }
}
