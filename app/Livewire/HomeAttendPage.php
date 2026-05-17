<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.mobile_view')]
class HomeAttendPage extends Component
{
    public function render()
    {
        return view('livewire.home-attend-page');
    }
}
