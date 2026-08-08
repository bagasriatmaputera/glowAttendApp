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
                <x-text-input wire:model.live.debounce.300ms="search" placeholder="Cari pengumuman..." class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/80 backdrop-blur-sm" />
            </div>
        </div>
        <button wire:click="create"
            class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transform hover:scale-105">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Tambah Pengumuman') }}
            </span>
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl shadow-indigo-200/20 border border-indigo-100/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-indigo-100">
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Judul</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Konten</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-indigo-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($announcements as $announcement)
                        @php
                            $isPending = in_array($announcement->id, $pendingAnnouncementIds ?? []);
                        @endphp
                        <tr class="hover:bg-gradient-to-r hover:from-indigo-50/50 hover:to-purple-50/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900">{{ $announcement->title }}</span>
                            </td>
                            <td class="px-6 py-4 max-w-md">
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $announcement->content }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if ($announcement->is_active)
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
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $announcement->created_at->translatedFormat('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2 items-center">
                                    <button wire:click="edit({{ $announcement->id }})"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Ubah
                                    </button>
                                    @if ($isPending)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-amber-400 to-orange-500 text-white shadow-lg shadow-amber-500/25">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Menunggu
                                        </span>
                                    @else
                                        <button wire:click="confirmDelete({{ $announcement->id }})"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-150">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            {{ $isOwner ? 'Hapus' : 'Ajukan Hapus' }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pengumuman</h3>
                                    <p class="text-gray-500">Tambahkan pengumuman pertama Anda.</p>
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
        {{ $announcements->links() }}
    </div>

    <!-- Create/Edit Modal -->
    <div x-data="{ open: @entangle('showAnnouncementModal') }" class="p-4" x-cloak>
    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="relative z-10 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">

                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6 border-b border-indigo-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-1">
                                {{ $announcement_id ? 'Edit Pengumuman' : 'Tambah Pengumuman' }}
                            </h2>
                            <p class="text-indigo-100">
                                {{ $announcement_id ? 'Perbarui informasi pengumuman' : 'Buat pengumuman baru untuk karyawan' }}
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

                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-100">
                            <h3 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5zM5 5a2 2 0 00-2 2v9a2 2 0 002 2h9a2 2 0 002-2v-4a1 1 0 10-2 0v4H5V7h4a1 1 0 100-2H5z" clip-rule="evenodd"></path>
                                </svg>
                                Detail Pengumuman
                            </h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Judul <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="title"
                                           placeholder="contoh: Penyesuaian Jam Kerja"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                    @error('title')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Konten <span class="text-red-500">*</span>
                                    </label>
                                    <textarea wire:model="content" rows="5"
                                              placeholder="Tulis isi pengumuman..."
                                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors resize-none"></textarea>
                                    @error('content')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex items-center space-x-3 bg-white/80 backdrop-blur-sm px-6 py-4 rounded-xl border border-gray-200">
                                    <input type="checkbox" wire:model="is_active"
                                           id="is_active"
                                           class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                                    <label for="is_active" class="text-sm font-semibold text-gray-700 cursor-pointer">
                                        Pengumuman Aktif
                                    </label>
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
                                    {{ $announcement_id ? 'Perbarui Pengumuman' : 'Simpan Pengumuman' }}
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
                        <h2 class="text-xl font-bold text-white">Hapus Pengumuman</h2>
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
                            @if ($isOwner)
                                Tindakan ini tidak dapat dibatalkan. Pengumuman akan dihapus secara permanen.
                            @else
                                Pengumuman tidak akan dihapus langsung. Permintaan penghapusan akan dikirim ke Owner untuk mendapat persetujuan terlebih dahulu.
                            @endif
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
                                Hapus Pengumuman
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
    </div>
</div>
