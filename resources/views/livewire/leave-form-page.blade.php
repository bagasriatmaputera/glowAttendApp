<div class="min-h-screen bg-gray-50 flex flex-col font-sans pb-24 relative">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#d95c37] to-[#2b3058] pt-12 pb-6 px-6 rounded-b-[2rem] shadow-sm text-white">
        <div class="flex items-center gap-3 max-w-md mx-auto">
            <a href="{{ route('home') }}" class="p-1 hover:bg-white/10 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-lg font-bold tracking-wide">Daftar Cuti Anda</h1>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-md mx-auto w-full px-5 mt-6 flex-1 flex flex-col gap-4">
        
        @if (session()->has('message'))
            <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 text-green-800 rounded-2xl flex items-center shadow-sm animate-pulse">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-bold text-sm text-green-800">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 bg-red-50/80 backdrop-blur-md border border-red-200 text-red-800 rounded-2xl flex items-center shadow-sm">
                <div class="bg-red-100 p-2 rounded-full mr-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <span class="font-bold text-sm text-red-800">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Action & Search Section -->
        <div class="flex flex-col gap-3">
            <button wire:click="openModal" class="w-full py-3 bg-[#6366f1] text-white font-bold rounded-xl hover:bg-[#5355d1] transition-all duration-200 shadow-lg shadow-indigo-500/25 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajukan Cuti Baru
            </button>

            <!-- Search input -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari status atau tipe..." class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6366f1] focus:border-transparent bg-white shadow-sm text-sm" />
            </div>
        </div>

        <!-- Requests Record Table/List -->
        <div class="flex flex-col gap-3.5">
            @forelse($requests as $req)
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col gap-3">
                    <div class="flex justify-between items-center">
                        <div>
                            @switch($req->leave_type)
                                @case('sick')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-600 border border-red-100">Cuti Sakit</span>
                                    @break
                                @case('annual')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-100">Cuti Tahunan</span>
                                    @break
                                @default
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-orange-50 text-orange-600 border border-orange-100">Cuti Darurat</span>
                            @endswitch
                        </div>
                        <div>
                            @switch($req->status)
                                @case('approved')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-600 border border-green-100">Disetujui</span>
                                    @break
                                @case('rejected')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-600 border border-red-100">Ditolak</span>
                                    @break
                                @default
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-100">Pending</span>
                            @endswitch
                        </div>
                    </div>

                    <!-- Dates & Reason -->
                    <div class="text-xs text-gray-500 border-t border-gray-50 pt-2 flex flex-col gap-1">
                        <div class="flex justify-between">
                            <span class="font-semibold">Mulai:</span>
                            <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($req->start_date)->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Selesai:</span>
                            <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($req->end_date)->translatedFormat('d M Y') }}</span>
                        </div>
                        
                        @if($req->attachment_url)
                            <div class="flex justify-between items-center mt-1">
                                <span class="font-semibold">Lampiran:</span>
                                <a href="{{ $req->attachment_url }}" target="_blank" class="text-xs text-[#6366f1] font-bold hover:underline flex items-center gap-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Lihat Dokumen
                                </a>
                            </div>
                        @endif

                        <div class="mt-1.5 text-gray-600 italic bg-gray-50 p-2.5 rounded-lg border border-gray-100/50">
                            "{{ $req->reason }}"
                        </div>
                    </div>

                    <!-- Cancel Action button (only if pending AND start date is not in the past) -->
                    @if($req->status === 'pending' && \Carbon\Carbon::parse($req->start_date)->startOfDay()->gte(\Carbon\Carbon::today()))
                        <div class="border-t border-gray-50 pt-2 flex justify-end">
                            <button wire:click="cancelRequest({{ $req->id }})" wire:confirm="Apakah Anda yakin ingin membatalkan pengajuan cuti ini?" class="px-3.5 py-1.5 text-xs font-bold text-red-500 bg-red-50 hover:bg-red-100 rounded-lg border border-red-100 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Batalkan Pengajuan
                            </button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="text-sm text-gray-500 font-semibold">Belum ada riwayat pengajuan cuti.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-2">
            {{ $requests->links() }}
        </div>
    </div>

    <!-- Leave Request Modal -->
    <div x-data="{ open: @entangle('showLeaveFormModal') }" x-cloak>
        <div x-show="open" class="fixed inset-0 z-50 flex items-end justify-center sm:items-center p-4">
            <!-- Modal Overlay -->
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" wire:click="closeModal" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <!-- Modal Content -->
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95" class="relative bg-white w-full max-w-md rounded-t-[2rem] sm:rounded-2xl shadow-2xl z-10 overflow-hidden flex flex-col max-h-[85vh]">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-bold">Pengajuan Cuti Baru</h3>
                        <p class="text-[11px] text-indigo-100">Silakan isi formulir di bawah ini</p>
                    </div>
                    <button wire:click="closeModal" class="p-1 hover:bg-white/10 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Body -->
                <div class="p-5 overflow-y-auto space-y-4">
                    <form wire:submit.prevent="store" class="space-y-4">
                        <!-- Leave Type -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Jenis Cuti <span class="text-red-500">*</span></label>
                            <select wire:model.live="leave_type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6366f1] focus:border-transparent bg-white text-xs font-semibold text-gray-700">
                                <option value="sick">Cuti Sakit</option>
                                <option value="annual">Cuti Tahunan</option>
                                <option value="emergency">Cuti Darurat</option>
                            </select>
                            @error('leave_type')
                                <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" wire:model.live="start_date" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6366f1] focus:border-transparent bg-white text-xs font-semibold text-gray-700">
                            @error('start_date')
                                <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" wire:model.live="end_date" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6366f1] focus:border-transparent bg-white text-xs font-semibold text-gray-700">
                            @error('end_date')
                                <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Dynamic Attachment File Upload (Reactive when Sick Leave and Duration > 1 day) -->
                        @if($leave_type === 'sick' && $this->duration > 1)
                            <div class="p-3 bg-red-50/50 border border-red-100 rounded-2xl space-y-2">
                                <label class="block text-[11px] font-bold text-red-600 uppercase tracking-wider">Surat Keterangan Dokter <span class="text-red-500">*</span></label>
                                <input type="file" wire:model="attachment" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-red-100 file:text-red-700 hover:file:bg-red-200" />
                                <span class="text-[9px] text-gray-400 block">Dukungan format PDF/Gambar maks 2MB.</span>
                                @error('attachment')
                                    <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <!-- Reason -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Alasan Cuti <span class="text-red-500">*</span></label>
                            <textarea wire:model="reason" placeholder="Jelaskan alasan pengajuan cuti Anda..." rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6366f1] focus:border-transparent bg-white text-xs font-medium text-gray-700 resize-none"></textarea>
                            @error('reason')
                                <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-3 border-t border-gray-100">
                            <button type="button" wire:click="closeModal" class="flex-1 py-2.5 border border-gray-200 text-gray-500 rounded-xl font-bold text-xs hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 py-2.5 bg-[#6366f1] text-white rounded-xl font-bold text-xs hover:bg-[#5355d1] transition-colors shadow-lg shadow-indigo-500/20">
                                Ajukan Cuti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 z-50">
        <livewire:navigation.nav-bottom />
    </div>
</div>
