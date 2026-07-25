<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.mobile_view')]
class NotificationPage extends Component
{
    use WithPagination;

    public function markAsRead($id)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return;
        }

        $notification = Notification::where('employee_id', $employee->id)
            ->where('id', $id)
            ->first();

        if ($notification && !$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function markAllAsRead()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return;
        }

        Notification::where('employee_id', $employee->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        session()->flash('message', 'Semua notifikasi ditandai telah dibaca.');
    }

    public function deleteNotification($id)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return;
        }

        Notification::where('employee_id', $employee->id)
            ->where('id', $id)
            ->delete();
    }

    public function render()
    {
        $employee = Auth::user()->employee;
        $notifications = null;

        if ($employee) {
            $notifications = Notification::where('employee_id', $employee->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('livewire.notification-page', [
            'notifications' => $notifications,
        ]);
    }
}
