<?php

namespace App\Livewire\Announcement;

use App\Models\Announcement;
use App\Models\PendingChangeRequest;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public bool $isOwner = false;

    public $search = '';

    public function mount()
    {
        $this->isOwner = auth()->user()->isOwner();
    }

    public $announcement_id;

    public $title;

    public $content;

    public $is_active = true;

    public $showAnnouncementModal = false;

    public $showDeleteModal = false;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'title.required' => 'Judul pengumuman wajib diisi.',
        'content.required' => 'Konten pengumuman wajib diisi.',
    ];

    public function create()
    {
        $this->resetInputFields();
        $this->showAnnouncementModal = true;
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);

        $this->announcement_id = $id;
        $this->title = $announcement->title;
        $this->content = $announcement->content;
        $this->is_active = $announcement->is_active;

        $this->showAnnouncementModal = true;
    }

    public function store()
    {
        $this->validate();

        $isUpdate = (bool) $this->announcement_id;

        if ($isUpdate) {
            Announcement::findOrFail($this->announcement_id)->update([
                'title' => $this->title,
                'content' => $this->content,
                'is_active' => $this->is_active,
            ]);
        } else {
            Announcement::create([
                'title' => $this->title,
                'content' => $this->content,
                'is_active' => $this->is_active,
                'created_by' => auth()->id(),
            ]);
        }

        $this->dispatch('toast', type: 'success', message: $isUpdate
            ? 'Pengumuman berhasil diperbarui.'
            : 'Pengumuman berhasil ditambahkan.');

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->announcement_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->announcement_id) {
            $announcement = Announcement::findOrFail($this->announcement_id);

            if ($this->isOwner) {
                $announcement->delete();
                $this->dispatch('toast', type: 'success', message: 'Pengumuman berhasil dihapus.');
            } else {
                PendingChangeRequest::create([
                    'type' => PendingChangeRequest::TYPE_DELETE_ANNOUNCEMENT,
                    'payload' => [
                        'announcement_id' => $announcement->id,
                        'title' => $announcement->title,
                    ],
                    'requested_by' => auth()->id(),
                    'status' => PendingChangeRequest::STATUS_PENDING,
                ]);
                $this->dispatch('toast', type: 'info', message: 'Permintaan penghapusan dikirim ke Owner untuk disetujui.');
            }
        }
        $this->closeDeleteModal();
        $this->announcement_id = null;
    }

    public function closeModal()
    {
        $this->showAnnouncementModal = false;
        $this->showDeleteModal = false;
        $this->resetInputFields();
        $this->resetValidation();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->announcement_id = null;
    }

    private function resetInputFields()
    {
        $this->announcement_id = null;
        $this->title = '';
        $this->content = '';
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.announcement.index', [
            'announcements' => Announcement::with('creator')
                ->where('title', 'like', '%'.$this->search.'%')
                ->orWhere('content', 'like', '%'.$this->search.'%')
                ->latest()
                ->paginate(10),
            'pendingAnnouncementIds' => PendingChangeRequest::pending()
                ->where('type', PendingChangeRequest::TYPE_DELETE_ANNOUNCEMENT)
                ->get()
                ->map(fn ($request) => $request->payload['announcement_id'] ?? null)
                ->filter()
                ->all(),
        ])->layout('layouts.app', [
            'header' => 'Pengumuman',
        ]);
    }
}
