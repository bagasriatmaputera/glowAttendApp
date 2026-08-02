<div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="w-full sm:w-1/2 lg:w-1/3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <x-text-input wire:model.live.debounce.300ms="search" placeholder="Cari lokasi..." class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/80 backdrop-blur-sm" />
            </div>
        </div>
        <button wire:click="create"
            class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transform hover:scale-105">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Tambah Lokasi') }}
            </span>
        </button>
    </div>

    <!-- Info banner -->
    <div class="mb-6 p-4 bg-blue-50/80 border border-blue-100 rounded-xl text-sm text-blue-700">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Hanya <strong>satu lokasi aktif</strong> yang dipakai untuk validasi Clock In/Out. Lokasi aktif otomatis menonaktifkan lokasi lainnya.</span>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl shadow-indigo-200/20 border border-indigo-100/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-indigo-100">
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Alamat</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Koordinat</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Radius</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Jam Masuk</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Jam Keluar</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-indigo-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($officeLocations as $location)
                        <tr class="hover:bg-gradient-to-r hover:from-indigo-50/50 hover:to-purple-50/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900">{{ $location->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $location->address ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500 font-mono">{{ $location->latitude }}, {{ $location->longitude }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $location->radius }} m</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $location->clock_in_time?->format('H:i') ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $location->clock_out_time?->format('H:i') ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($location->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow-lg shadow-green-500/25">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow-lg shadow-gray-500/25">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <button wire:click="edit({{ $location->id }})"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Ubah
                                    </button>
                                    <button wire:click="confirmDelete({{ $location->id }})"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada lokasi kantor</h3>
                                    <p class="text-gray-500">Tambahkan lokasi kantor pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $officeLocations->links() }}
    </div>

    <!-- Create/Edit Modal -->
    <div x-data="{ open: @entangle('showOfficeLocationModal') }" class="p-4" x-cloak>
    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="relative z-10 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">

                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6 border-b border-indigo-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-1">
                                {{ $office_location_id ? 'Edit Lokasi Kantor' : 'Tambah Lokasi Kantor' }}
                            </h2>
                            <p class="text-indigo-100">
                                {{ $office_location_id ? 'Perbarui informasi lokasi kantor' : 'Tambahkan lokasi kantor baru' }}
                            </p>
                        </div>
                        <button wire:click="closeModal"
                                class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-colors duration-150">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-8 overflow-y-auto max-h-[calc(90vh-200px)]">
                    <form wire:submit.prevent="store" class="space-y-8">

                        <!-- Pilih Lokasi di Peta -->
                        <div x-data="officeMap" x-effect="if (open) { setTimeout(() => openMap(), 300); }" class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-6 border border-emerald-100">
                            <h3 class="text-lg font-semibold text-emerald-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                Pilih Lokasi di Peta
                            </h3>

                            <div wire:ignore class="space-y-3">
                                <div class="relative">
                                    <input type="text" x-model="searchQuery" @keydown.enter.prevent="searchLocation()"
                                           placeholder="Cari nama lokasi / alamat..."
                                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white text-sm transition-colors">
                                    <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>

                                <div x-show="searchResults.length > 0" class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-lg max-h-52 overflow-y-auto">
                                    <template x-for="r in searchResults" :key="r.place_id">
                                        <button type="button" @click="selectResult(r)"
                                                class="w-full text-left px-4 py-2.5 hover:bg-emerald-50 text-sm text-gray-700 border-b border-gray-100 last:border-0"
                                                x-text="r.display_name"></button>
                                    </template>
                                </div>

                                <div x-show="searching" class="text-sm text-gray-500 flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Mencari lokasi...
                                </div>
                                <div x-show="searchError" class="text-sm text-red-600" x-text="searchError"></div>

                                <div x-ref="mapEl" class="h-80 w-full rounded-xl border border-gray-200 z-0"></div>

                                <div class="flex flex-wrap gap-2">
                                    <button type="button" @click="useMyLocation()"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-emerald-300 text-emerald-700 text-sm font-semibold hover:bg-emerald-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Lokasi Saya
                                    </button>
                                    <button type="button" @click="resetCoords()"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-gray-300 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Reset ke Default
                                    </button>
                                </div>
                            </div>

                            <!-- Display info untuk input manual -->
                            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs text-amber-800 space-y-1.5">
                                <p class="font-bold flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Panduan Akurasi Koordinat
                                </p>
                                <p>• Gunakan format <strong>desimal derajat</strong>. Latitude: <strong>-90 s.d 90</strong>, Longitude: <strong>-180 s.d 180</strong>.</p>
                                <p>• Di Google Maps: klik kanan pada lokasi, lalu klik nilai koordinat (otomatis tersalin).</p>
                                <p>• Setelah mengetik manual, perhatikan pratinjau marker di peta untuk memastikan posisi akurat.</p>
                                <div class="border-t border-amber-200 pt-2 mt-1">
                                    <p><strong>Koordinat saat ini:</strong> <span class="font-mono" x-text="coordinateText"></span></p>
                                    <p><strong>Format DMS:</strong> <span class="font-mono" x-text="dmsText"></span></p>
                                </div>
                            </div>

                            <!-- Input manual latitude / longitude -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Latitude <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" step="any" wire:model="latitude" x-ref="latInput" @input="onManualChange()"
                                           placeholder="contoh: -6.2087634"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white/80 backdrop-blur-sm transition-colors">
                                    @error('latitude')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Longitude <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" step="any" wire:model="longitude" x-ref="lngInput" @input="onManualChange()"
                                           placeholder="contoh: 106.845599"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white/80 backdrop-blur-sm transition-colors">
                                    @error('longitude')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-100">
                            <h3 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                Informasi Lokasi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Lokasi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="name"
                                           placeholder="contoh: Kantor Pusat Glowttend"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                    @error('name')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Alamat
                                    </label>
                                    <input type="text" wire:model="address"
                                           placeholder="contoh: Jl. Sudirman No. 88, Jakarta"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                    @error('address')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Radius (meter) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" wire:model="radius"
                                           placeholder="contoh: 100"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                    @error('radius')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex items-center justify-center">
                                    <div class="flex items-center space-x-3 bg-white/80 backdrop-blur-sm px-6 py-4 rounded-xl border border-gray-200">
                                        <input type="checkbox" wire:model="is_active"
                                               id="is_active"
                                               class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                                        <label for="is_active" class="text-sm font-semibold text-gray-700 cursor-pointer">
                                            Lokasi Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                            <h3 class="text-lg font-semibold text-purple-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Jam Kerja
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jam Masuk <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" wire:model="clock_in_time"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                    @error('clock_in_time')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jam Keluar <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" wire:model="clock_out_time"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                    @error('clock_out_time')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <button type="button" wire:click="closeModal"
                                    class="px-8 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition-colors duration-150">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 font-semibold transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transform hover:scale-105">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $office_location_id ? 'Perbarui Lokasi' : 'Simpan Lokasi' }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ open: @entangle('showDeleteModal') }" class="p-4" x-cloak>
    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="relative z-10 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

                <div class="bg-gradient-to-r from-red-500 to-pink-600 px-8 py-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-white">Hapus Lokasi Kantor</h2>
                        <button wire:click="closeDeleteModal"
                                class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-colors duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto bg-red-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>

                    <div class="text-center mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Apakah Anda benar-benar yakin?</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Tindakan ini tidak dapat dibatalkan. Lokasi kantor akan dihapus secara permanen.
                        </p>
                    </div>

                    <div class="flex justify-center space-x-4">
                        <button wire:click="closeDeleteModal"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition-colors duration-150">
                            Batal
                        </button>
                        <button wire:click="delete"
                                class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-xl hover:from-red-600 hover:to-pink-700 font-semibold transition-all duration-200 shadow-lg shadow-red-500/25 hover:shadow-red-500/40 transform hover:scale-105">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus Lokasi
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
    </div>
</div>
