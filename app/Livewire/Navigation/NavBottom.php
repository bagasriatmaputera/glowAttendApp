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
            'nama' => 'Profile',
            'route' => 'profile',
            'icon' => 'user',
        ],
        [
            'nama' => 'Setting',
            'route' => 'settings',
            'icon' => 'settings',
        ],
    ];
    
    public function render()
    {
        return view('livewire.navigation.nav-bottom');
    }
}
