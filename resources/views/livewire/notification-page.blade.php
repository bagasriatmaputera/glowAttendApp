<div class="min-h-screen bg-gray-50 flex flex-col font-sans pb-24">
    <!-- Header -->
    <div class="relative bg-gradient-to-br from-[#d95c37] to-[#2b3058] pt-12 pb-8 px-6 rounded-b-[2rem] shadow-sm">
        <div class="flex items-center gap-4 text-white max-w-md mx-auto">
            <a href="{{ route('home') }}" class="p-2 bg-white/10 rounded-full hover:bg-white/20 transition-all shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-[18px] font-extrabold tracking-wide">Notifikasi Inbox</h1>
                <p class="text-[12px] opacity-80">Kelola dan lihat pesan masuk Anda</p>
            </div>
            @if($notifications && $notifications->where('is_read', false)->count() > 0)
                <button wire:click="markAllAsRead" class="text-[12px] font-bold bg-white/20 hover:bg-white/30 transition-all px-3 py-1.5 rounded-full">
                    Baca Semua
                </button>
            @endif
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-md mx-auto w-full px-5 mt-6 flex-1 flex flex-col gap-4">
        @if (session()->has('message'))
            <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 text-green-800 rounded-2xl flex items-center shadow-sm">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-bold text-sm">{{ session('message') }}</span>
            </div>
        @endif

        <div class="flex-1 flex flex-col gap-3">
            @if($notifications && $notifications->count() > 0)
                @foreach($notifications as $notification)
                    <div wire:key="notif-{{ $notification->id }}" 
                         wire:click="markAsRead({{ $notification->id }})"
                         class="relative p-4 rounded-2xl border transition-all cursor-pointer flex gap-4 {{ $notification->is_read ? 'bg-white border-gray-100' : 'bg-indigo-50/40 border-indigo-100 hover:bg-indigo-50/60' }}">
                        
                        <!-- Status indicator dot -->
                        @if(!$notification->is_read)
                            <div class="absolute top-4 right-4 w-2.5 h-2.5 bg-[#6366f1] rounded-full animate-pulse"></div>
                        @endif

                        <!-- Icon based on type -->
                        <div class="shrink-0">
                            @if($notification->type === 'leave_request')
                                <div class="p-3 rounded-xl bg-orange-100 text-orange-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @elseif($notification->type === 'attendance')
                                <div class="p-3 rounded-xl bg-red-100 text-red-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                </div>
                            @else
                                <div class="p-3 rounded-xl bg-blue-100 text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            @endif
                        </div>

                        <!-- Message Content -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-[14px] font-bold text-gray-900 leading-snug flex items-center gap-1.5">
                                {{ $notification->title }}
                            </h3>
                            <p class="text-[13px] text-gray-600 mt-1 leading-relaxed">{{ $notification->message }}</p>
                            <span class="text-[11px] text-gray-400 font-semibold block mt-2">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <button wire:click.stop="deleteNotification({{ $notification->id }})" class="shrink-0 p-1.5 text-gray-400 hover:text-red-500 rounded-full hover:bg-gray-100 self-start transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-800">Inbox Kosong</h3>
                    <p class="text-xs text-gray-400 mt-1">Anda belum menerima notifikasi apapun.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 z-50">
        <livewire:navigation.nav-bottom />
    </div>
</div>
