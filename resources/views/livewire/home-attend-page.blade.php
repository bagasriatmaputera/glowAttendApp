<div id="home-attend-wrapper" class="min-h-screen bg-gray-50 flex flex-col font-sans pb-24">
    
    <!-- Header Background -->
    <div class="relative bg-gradient-to-br from-[#d95c37] to-[#2b3058] pt-12 pb-24 px-6 rounded-b-[2rem] shadow-sm">
        <div class="flex justify-between items-start text-white max-w-md mx-auto">
            <div>
                <h1 class="text-[17px] font-normal">Welcome, <span class="font-bold tracking-wide">{{ auth()->user()->employee->full_name ?? auth()->user()->username }}</span></h1>
                <p class="text-[13px] mt-1 font-light opacity-90">Don't forget to record your attendance!</p>
            </div>
            <button wire:click="logout" class="flex items-center gap-1.5 text-[14px] font-medium hover:opacity-80 transition-opacity">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Sign Out
            </button>
        </div>
    </div>

    <!-- Main Content Overlapping Header -->
    <div class="max-w-md mx-auto w-full px-5 -mt-16 z-10 flex-1 flex flex-col gap-6" x-data="{ 
        gettingLocation: false, 
        locationError: '', 
        handleClock(type) {
            this.gettingLocation = true;
            this.locationError = '';
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.gettingLocation = false;
                        if (type === 'in') {
                            $wire.clockIn(position.coords.latitude, position.coords.longitude)
                                .catch((error) => {
                                    this.gettingLocation = false;
                                    this.locationError = 'Gagal Clock In, silakan coba lagi.';
                                });
                        } else {
                            $wire.clockOut(position.coords.latitude, position.coords.longitude)
                                .catch((error) => {
                                    this.gettingLocation = false;
                                    this.locationError = 'Gagal Clock Out, silakan coba lagi.';
                                });
                        }
                    },
                    (error) => {
                        this.gettingLocation = false;
                        let errorMsg = 'Gagal mendapatkan lokasi: ';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMsg += 'Anda menolak permintaan akses lokasi.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMsg += 'Informasi lokasi tidak tersedia (GPS mati atau tidak ada sinyal).';
                                break;
                            case error.TIMEOUT:
                                errorMsg += 'Waktu permintaan lokasi habis (timeout).';
                                break;
                            default:
                                errorMsg += error.message;
                                break;
                        }
                        this.locationError = errorMsg;
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 0
                    }
                );
            } else {
                this.gettingLocation = false;
                this.locationError = 'Browser Anda tidak mendukung Geolocation.';
            }
        }
    }">
        
        <!-- Alerts & Loading Indicators -->
        @if (session()->has('message'))
            <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 text-green-800 rounded-2xl flex items-center shadow-sm">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-bold text-sm">{{ session('message') }}</span>
            </div>
        @endif
        @if ($errorMessage)
            <div class="p-4 bg-red-50/80 backdrop-blur-md border border-red-200 text-red-800 rounded-2xl flex items-center shadow-sm">
                <div class="bg-red-100 p-2 rounded-full mr-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <span class="font-bold text-sm">{{ $errorMessage }}</span>
            </div>
        @endif

        <div x-show="gettingLocation" style="display: none;" class="p-4 bg-indigo-50/80 backdrop-blur-md border border-indigo-200 text-indigo-800 rounded-2xl flex items-center justify-center shadow-sm">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="font-bold text-sm">Mengakses GPS Anda...</span>
        </div>

        <div x-show="locationError" style="display: none;" class="p-4 bg-red-50/80 backdrop-blur-md border border-red-200 text-red-800 rounded-2xl flex items-center shadow-sm">
            <div class="bg-red-100 p-2 rounded-full mr-3">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
            </div>
            <span class="font-bold text-sm" x-text="locationError"></span>
        </div>

        <!-- Attendance Card -->
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 border border-gray-100">
            <!-- Current Date & Time -->
            <div class="flex items-center gap-2 text-[13px] font-bold text-gray-700 mb-3">
                <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>{{ now()->format('l, d F Y') }}</span>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <!-- Clock In -->
                <button @click="handleClock('in')"
                        @if($todayAttendance) disabled @endif
                        class="flex-1 flex items-center p-3.5 border-2 rounded-xl transition-all {{ $todayAttendance ? 'border-gray-100 bg-gray-50/50 text-gray-400 cursor-not-allowed' : 'border-[#6366f1] text-[#6366f1] hover:bg-[#6366f1]/5' }}">
                    <div class="mr-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </div>
                    <div class="text-left">
                        <div class="text-[14px] font-semibold tracking-wide">Clock In</div>
                        <div class="text-[12px] opacity-60 font-medium">{{ $todayAttendance ? $todayAttendance->clock_in->format('H:i') : '--:--' }}</div>
                    </div>
                </button>
                
                <!-- Clock Out -->
                <button @click="handleClock('out')"
                        @if(!$todayAttendance || $todayAttendance->clock_out) disabled @endif
                        class="flex-1 flex items-center p-3.5 border-2 rounded-xl transition-all {{ (!$todayAttendance || $todayAttendance->clock_out) ? 'border-gray-100 bg-gray-50/50 text-gray-400 cursor-not-allowed' : 'border-[#6366f1] text-[#6366f1] hover:bg-[#6366f1]/5' }}">
                    <div class="mr-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </div>
                    <div class="text-left">
                        <div class="text-[14px] font-semibold tracking-wide">Clock Out</div>
                        <div class="text-[12px] opacity-60 font-medium">{{ ($todayAttendance && $todayAttendance->clock_out) ? $todayAttendance->clock_out->format('H:i') : '--:--' }}</div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Announcement Section -->
        <div class="mt-4 flex flex-col gap-3">
            <div class="flex justify-between items-center">
                <h3 class="text-[16px] font-extrabold text-gray-900">Announcement</h3>
                <a href="{{ route('announcements.list') }}" class="text-[14px] font-bold text-[#6366f1] hover:underline">View All</a>
            </div>

            @if(count($announcements) > 0)
                <div class="flex flex-col gap-3">
                    @foreach($announcements as $announcement)
                        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
                            <div class="flex justify-between items-start gap-3">
                                <h4 class="text-[13.5px] font-extrabold text-gray-900 leading-tight">
                                    {{ $announcement->title }}
                                </h4>
                                <span class="text-[11px] text-gray-400 font-semibold shrink-0">
                                    {{ $announcement->created_at->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            <p class="text-[12px] text-gray-500 mt-1 leading-snug line-clamp-2">
                                {{ $announcement->content }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-[0_2px_8px_rgba(0,0,0,0.01)]">
                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                    <p class="text-xs font-bold text-gray-500">Belum ada pengumuman</p>
                </div>
            @endif
        </div>

        <!-- Inbox & Notifikasi Section -->
        <div class="mt-4 flex flex-col gap-3">
            <div class="flex justify-between items-center">
                <h3 class="text-[16px] font-extrabold text-gray-900">Inbox & Notifikasi</h3>
                <a href="{{ route('notifications') }}" class="text-[14px] font-bold text-[#6366f1] hover:underline flex items-center gap-1">
                    Lihat Semua
                    @if(count($notifications) > 0 && collect($notifications)->where('is_read', false)->count() > 0)
                        <span class="w-2.5 h-2.5 bg-red-500 rounded-full inline-block animate-pulse"></span>
                    @endif
                </a>
            </div>

            @if(count($notifications) > 0)
                <div class="flex flex-col gap-3">
                    @foreach($notifications as $notif)
                        <div wire:key="home-notif-{{ $notif->id }}" 
                             wire:click="markAsRead({{ $notif->id }})"
                             class="bg-white border rounded-2xl p-4 flex items-start gap-3.5 cursor-pointer transition-all {{ $notif->is_read ? 'border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)]' : 'border-indigo-100 bg-indigo-50/20 shadow-[0_4px_12px_rgba(99,102,241,0.04)]' }}">
                            
                            <!-- Type Icon -->
                            <div class="shrink-0 mt-0.5">
                                @if($notif->type === 'leave_request')
                                    <span class="p-2 rounded-xl bg-orange-50 text-orange-600 block">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </span>
                                @elseif($notif->type === 'attendance')
                                    <span class="p-2 rounded-xl bg-red-50 text-red-600 block">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                    </span>
                                @else
                                    <span class="p-2 rounded-xl bg-blue-50 text-blue-600 block">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start gap-2">
                                    <h4 class="text-[13px] font-extrabold text-gray-900 leading-tight truncate">
                                        {{ $notif->title }}
                                    </h4>
                                    <span class="text-[10px] text-gray-400 font-semibold shrink-0">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-[12px] text-gray-500 mt-1 leading-snug line-clamp-2">
                                    {{ $notif->message }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-[0_2px_8px_rgba(0,0,0,0.01)]">
                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2M9 20h6"></path></svg>
                    <p class="text-xs font-bold text-gray-500">Tidak ada notifikasi baru</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 z-50">
        <livewire:navigation.nav-bottom />
    </div>
</div>
