<div id="home-attend-wrapper">
    <div class="relative min-h-screen bg-gray-50 flex flex-col items-center overflow-hidden" x-data="clockApp()">
        <!-- Animated Background Gradients -->
        <div class="absolute top-[-20%] left-[-10%] w-[120%] h-[120%] bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-500 rounded-full blur-3xl opacity-20 animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[80%] h-[80%] bg-gradient-to-tl from-cyan-400 to-blue-500 rounded-full blur-3xl opacity-20 animate-pulse-slow" style="animation-delay: 2s;"></div>

        <div class="z-10 w-full max-w-[480px] mx-auto min-h-screen flex flex-col shadow-2xl bg-white/40 backdrop-blur-xl border border-white/50">
            <!-- Header Section -->
            <div class="px-8 py-10 bg-white/50 backdrop-blur-lg border-b border-white/40 rounded-b-[40px] shadow-sm">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                            Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">{{ auth()->user()->username }}</span>! 👋
                        </h1>
                        <p class="text-gray-600 font-medium mt-1">Jangan lupa catat kehadiranmu hari ini.</p>
                    </div>
                    <button wire:click="logout" class="p-3 bg-white/60 hover:bg-white/90 rounded-full backdrop-blur-md shadow-sm transition-all duration-300 hover:scale-110 text-gray-700 hover:text-red-500 group" title="Sign Out">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </button>
                </div>

                <!-- Live Clock -->
                <div class="flex flex-col items-center justify-center py-6">
                    <div class="text-6xl font-black text-gray-900 tracking-tighter drop-shadow-md" x-text="time"></div>
                    <div class="text-sm font-bold text-gray-500 mt-2 uppercase tracking-widest bg-gray-200/50 px-4 py-1.5 rounded-full" x-text="date"></div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="flex-1 px-8 py-8 flex flex-col gap-6" x-data="{ 
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
                                    $wire.clockIn(position.coords.latitude, position.coords.longitude);
                                } else {
                                    $wire.clockOut(position.coords.latitude, position.coords.longitude);
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
                <!-- Alerts -->
                @if (session()->has('message'))
                    <div class="p-4 bg-green-50/80 backdrop-blur-md border border-green-200 text-green-800 rounded-2xl flex items-center shadow-sm animate-fade-in">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="font-bold">{{ session('message') }}</span>
                    </div>
                @endif
                @if ($errorMessage)
                    <div class="p-4 bg-red-50/80 backdrop-blur-md border border-red-200 text-red-800 rounded-2xl flex items-center shadow-sm animate-fade-in">
                        <div class="bg-red-100 p-2 rounded-full mr-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <span class="font-bold">{{ $errorMessage }}</span>
                    </div>
                @endif

                <div x-show="gettingLocation" style="display: none;" class="p-4 bg-indigo-50/80 backdrop-blur-md border border-indigo-200 text-indigo-800 rounded-2xl flex items-center justify-center shadow-sm">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="font-bold">Mengakses GPS Anda...</span>
                </div>

                <div x-show="locationError" style="display: none;" class="p-4 bg-red-50/80 backdrop-blur-md border border-red-200 text-red-800 rounded-2xl flex items-center shadow-sm animate-fade-in">
                    <div class="bg-red-100 p-2 rounded-full mr-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                    </div>
                    <span class="font-bold" x-text="locationError"></span>
                </div>

                <!-- Clock Action Buttons -->
                <div class="grid grid-cols-2 gap-4 mt-2">
                    <!-- Clock In -->
                    <button @click="handleClock('in')" 
                            @if($todayAttendance) disabled @endif
                            class="relative group overflow-hidden rounded-[2rem] p-1 transition-all duration-300 {{ $todayAttendance ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.03] hover:shadow-2xl hover:shadow-indigo-500/40 active:scale-95' }}">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 opacity-100 group-hover:opacity-90 transition-opacity"></div>
                        <div class="relative h-full w-full bg-white/10 backdrop-blur-sm rounded-[1.75rem] p-6 flex flex-col items-center justify-center border border-white/20">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                <svg class="w-8 h-8 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <span class="text-white font-extrabold text-xl mb-1">{{ $todayAttendance ? 'Hadir' : 'Masuk' }}</span>
                            @if($todayAttendance)
                                <span class="bg-indigo-900/30 text-indigo-50 px-3 py-1 rounded-full font-bold text-sm shadow-inner">{{ $todayAttendance->clock_in->format('H:i') }}</span>
                            @endif
                        </div>
                    </button>

                    <!-- Clock Out -->
                    <button @click="handleClock('out')" 
                            @if(!$todayAttendance || $todayAttendance->clock_out) disabled @endif
                            class="relative group overflow-hidden rounded-[2rem] p-1 transition-all duration-300 {{ (!$todayAttendance || $todayAttendance->clock_out) ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.03] hover:shadow-2xl hover:shadow-rose-500/40 active:scale-95' }}">
                        <div class="absolute inset-0 bg-gradient-to-br from-rose-500 to-orange-500 opacity-100 group-hover:opacity-90 transition-opacity"></div>
                        <div class="relative h-full w-full bg-white/10 backdrop-blur-sm rounded-[1.75rem] p-6 flex flex-col items-center justify-center border border-white/20">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                <svg class="w-8 h-8 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <span class="text-white font-extrabold text-xl mb-1">{{ ($todayAttendance && $todayAttendance->clock_out) ? 'Pulang' : 'Pulang' }}</span>
                            @if($todayAttendance && $todayAttendance->clock_out)
                                <span class="bg-rose-900/30 text-rose-50 px-3 py-1 rounded-full font-bold text-sm shadow-inner">{{ $todayAttendance->clock_out->format('H:i') }}</span>
                            @endif
                        </div>
                    </button>
                </div>
                
                <!-- Spacer for bottom nav -->
                <div class="h-10"></div>
            </div>

            <!-- Bottom Navigation -->
                <div class="fixed bottom-0 w-full max-w-[480px] bg-white/80 backdrop-blur-xl border-t border-gray-200/50 pb-safe">
                <livewire:navigation.nav-bottom />
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('clockApp', () => ({
                    time: '',
                    date: '',
                    init() {
                        this.updateTime();
                        setInterval(() => this.updateTime(), 1000);
                    },
                    updateTime() {
                        const now = new Date();
                        this.time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                        this.date = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                    }
                }))
            })
        </script>
        <style>
            .animate-pulse-slow {
                animation: pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            @keyframes pulse {
                0%, 100% { opacity: 0.2; transform: scale(1); }
                50% { opacity: 0.3; transform: scale(1.05); }
            }
            .animate-fade-in {
                animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-15px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .pb-safe {
                padding-bottom: env(safe-area-inset-bottom);
            }
        </style>
    </div>
</div>
