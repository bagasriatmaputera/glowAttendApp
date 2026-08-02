<?php

namespace App\Livewire\Announcement;

use App\Models\Announcement;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.mobile_layout')]
class ListPage extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.announcement.list-page', [
            'announcements' => Announcement::active()->latest()->paginate(10),
        ]);
    }
}
