<?php

namespace App\Livewire\OfficeLocation;

use App\Models\OfficeLocation;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $office_location_id;
    public $name;
    public $address;
    public $latitude;
    public $longitude;
    public $radius = 100;
    public $clock_in_time;
    public $clock_out_time;
    public $is_active = true;

    public $showOfficeLocationModal = false;
    public $showDeleteModal = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:10|max:5000',
            'clock_in_time' => 'required|date_format:H:i',
            'clock_out_time' => 'required|date_format:H:i|after:clock_in_time',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama lokasi wajib diisi.',
        'latitude.required' => 'Latitude wajib diisi.',
        'latitude.between' => 'Latitude harus antara -90 dan 90.',
        'longitude.required' => 'Longitude wajib diisi.',
        'longitude.between' => 'Longitude harus antara -180 dan 180.',
        'radius.required' => 'Radius wajib diisi.',
        'radius.min' => 'Radius minimal 10 meter.',
        'clock_in_time.required' => 'Jam masuk wajib diisi.',
        'clock_out_time.required' => 'Jam keluar wajib diisi.',
        'clock_out_time.after' => 'Jam keluar harus setelah jam masuk.',
    ];

    public function create()
    {
        $this->resetInputFields();
        $this->showOfficeLocationModal = true;
    }

    public function edit($id)
    {
        $location = OfficeLocation::findOrFail($id);

        $this->office_location_id = $id;
        $this->name = $location->name;
        $this->address = $location->address;
        $this->latitude = $location->latitude;
        $this->longitude = $location->longitude;
        $this->radius = $location->radius;
        $this->clock_in_time = $location->clock_in_time?->format('H:i');
        $this->clock_out_time = $location->clock_out_time?->format('H:i');
        $this->is_active = $location->is_active;

        $this->showOfficeLocationModal = true;
    }

    public function store()
    {
        $this->validate();

        if ($this->is_active) {
            OfficeLocation::where('id', '!=', $this->office_location_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        OfficeLocation::updateOrCreate(
            ['id' => $this->office_location_id],
            [
                'name' => $this->name,
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'radius' => $this->radius,
                'clock_in_time' => $this->clock_in_time,
                'clock_out_time' => $this->clock_out_time,
                'is_active' => $this->is_active,
            ]
        );

        $this->dispatch('toast', type: 'success', message: $this->office_location_id
            ? 'Lokasi kantor berhasil diperbarui.'
            : 'Lokasi kantor berhasil ditambahkan.');

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->office_location_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->office_location_id) {
            OfficeLocation::findOrFail($this->office_location_id)->delete();
            $this->dispatch('toast', type: 'success', message: 'Lokasi kantor berhasil dihapus.');
        }
        $this->closeDeleteModal();
        $this->office_location_id = null;
    }

    public function closeModal()
    {
        $this->showOfficeLocationModal = false;
        $this->showDeleteModal = false;
        $this->resetInputFields();
        $this->resetValidation();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->office_location_id = null;
    }

    private function resetInputFields()
    {
        $this->office_location_id = null;
        $this->name = '';
        $this->address = '';
        $this->latitude = '';
        $this->longitude = '';
        $this->radius = 100;
        $this->clock_in_time = '';
        $this->clock_out_time = '';
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.office-location.index', [
            'officeLocations' => OfficeLocation::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('address', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10),
        ])->layout('layouts.app', [
            'header' => 'Lokasi Kantor',
        ]);
    }
}
