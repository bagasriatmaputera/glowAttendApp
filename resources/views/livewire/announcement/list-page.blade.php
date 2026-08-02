<div class="min-h-screen bg-gray-50 flex flex-col font-sans pb-24 relative">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#d95c37] to-[#2b3058] pt-12 pb-6 px-6 rounded-b-[2rem] shadow-sm text-white">
        <div class="flex items-center gap-3 max-w-md mx-auto">
            <a href="{{ route('home') }}" class="p-1 hover:bg-white/10 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-lg font-bold tracking-wide">Semua Pengumuman</h1>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-md mx-auto w-full px-5 mt-6 flex-1 flex flex-col gap-4">
        @forelse($announcements as $announcement)
            <div class="bg-white rounded-2xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-gray-100/80">
                <div class="flex justify-between items-start gap-3">
                    <h2 class="text-[14px] font-extrabold text-gray-900 leading-tight">
                        {{ $announcement->title }}
                    </h2>
                </div>
                <div class="flex items-center gap-1.5 mt-2">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-[11px] text-gray-400 font-semibold">
                        {{ $announcement->created_at->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <p class="text-[13px] text-gray-600 mt-3 leading-relaxed">
                    {{ $announcement->content }}
                </p>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
                <p class="text-sm text-gray-500 font-semibold">Belum ada pengumuman.</p>
            </div>
        @endforelse

        <div class="mt-2">
            {{ $announcements->links() }}
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 z-50">
        <livewire:navigation.nav-bottom />
    </div>
</div>
