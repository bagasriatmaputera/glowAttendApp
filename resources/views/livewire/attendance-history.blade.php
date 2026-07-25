<div class="min-h-screen bg-gray-50 flex flex-col font-sans pb-24 relative">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#d95c37] to-[#2b3058] pt-12 pb-6 px-6 rounded-b-[2rem] shadow-sm text-white">
        <div class="flex items-center gap-3 max-w-md mx-auto">
            <a href="{{ route('home') }}" class="p-1 hover:bg-white/10 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-lg font-bold tracking-wide">Riwayat Kehadiran</h1>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-md mx-auto w-full px-5 mt-6 flex-1 flex flex-col gap-4">
        
        <!-- Search & Filter -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari tanggal..." class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6366f1] focus:border-transparent bg-white shadow-sm text-sm" />
        </div>

        <!-- History List -->
        <div class="flex flex-col gap-3.5">
            @forelse($attendances as $attendance)
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col gap-3">
                    <div class="flex justify-between items-center">
                        <div class="text-sm font-bold text-gray-800">
                            {{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('l, d F Y') }}
                        </div>
                        <div>
                            @switch($attendance->status)
                                @case('present')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-600 border border-green-100">Hadir</span>
                                    @break
                                @case('late')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-100">Terlambat</span>
                                    @break
                                @case('on_leave')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-100">Cuti</span>
                                    @break
                                @default
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-600 border border-red-100">Absen</span>
                            @endswitch
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-50 pt-2.5">
                        <div class="flex flex-col">
                            <span class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider">Clock In</span>
                            <span class="text-sm font-bold text-gray-700 mt-0.5">
                                {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '--:--' }}
                            </span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider">Clock Out</span>
                            <span class="text-sm font-bold text-gray-700 mt-0.5">
                                {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '--:--' }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="text-sm text-gray-500 font-semibold">Tidak ada riwayat kehadiran.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 z-50">
        <livewire:navigation.nav-bottom />
    </div>
</div>
