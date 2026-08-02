<?php

namespace App\Livewire\Navigation;

use Livewire\Component;

class NavBottom extends Component
{
    public $menu = [];

    public function mount()
    {
        $roleName = auth()->user()->roles->first()?->name ?? 'Employee';

        if (in_array($roleName, ['Management', 'Admin'])) {
            $this->menu = [
                [
                    'nama' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'chart-bar',
                ],
                [
                    'nama' => 'Cuti',
                    'route' => 'leave-requests.index',
                    'icon' => 'calendar',
                ],
                [
                    'nama' => 'Absen',
                    'route' => 'attendance.index',
                    'icon' => 'clock',
                ],
                [
                    'nama' => 'Karyawan',
                    'route' => 'employees',
                    'icon' => 'users',
                ],
                [
                    'nama' => 'Profil',
                    'route' => 'profile',
                    'icon' => 'user',
                ],
            ];
        } else {
            $this->menu = [
                [
                    'nama' => 'Home',
                    'route' => 'home',
                    'icon' => 'home',
                ],
                [
                    'nama' => 'Cuti',
                    'route' => 'leave-form-page',
                    'icon' => 'calendar',
                ],
                [
                    'nama' => 'Riwayat',
                    'route' => 'history',
                    'icon' => 'clock',
                ],
                [
                    'nama' => 'Inbox',
                    'route' => 'notifications',
                    'icon' => 'bell',
                ],
                [
                    'nama' => 'Profil',
                    'route' => 'employee.profile',
                    'icon' => 'user',
                ],
            ];
        }
    }

    public function render()
    {
        return view('livewire.navigation.nav-bottom');
    }
}
