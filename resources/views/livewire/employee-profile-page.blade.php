<div id="profile-wrapper">
    <div class="relative min-h-screen bg-gradient-to-br from-[#e0c3fc] via-[#f3e7e9] to-[#8ec5fc] flex flex-col items-center overflow-hidden">
        <!-- Abstract Glass Orbs Background -->
        <div class="absolute top-[-10%] left-[-20%] w-[80%] h-[80%] bg-purple-400 rounded-full blur-[100px] opacity-30 animate-pulse-slow"></div>
        <div class="absolute bottom-[20%] right-[-10%] w-[60%] h-[60%] bg-blue-400 rounded-full blur-[100px] opacity-30 animate-pulse-slow" style="animation-delay: 2s;"></div>
        <div class="absolute top-[40%] left-[20%] w-[70%] h-[70%] bg-white rounded-full blur-[80px] opacity-50"></div>

        <div class="z-10 w-full max-w-[480px] mx-auto min-h-screen flex flex-col pb-24 px-6 pt-12">
            
            <!-- Header Section (Floating) -->
            <div class="flex flex-col items-center relative mb-8 z-20">
                <!-- Avatar -->
                <div class="relative mb-4">
                    <div class="w-24 h-24 rounded-full shadow-[0_0_30px_rgba(109,40,217,0.4)] border-[3px] border-white/50 bg-violet-600 flex items-center justify-center text-white font-medium text-3xl overflow-hidden relative">
                        <!-- Initials instead of image for the exact look, or keep image if desired. Let's use image with ui-avatars -->
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->full_name) }}&background=6d28d9&color=fff&size=128&font-size=0.4&bold=true" alt="User Avatar" class="w-full h-full object-cover">
                    </div>
                    <!-- Online Indicator -->
                    @if($employee->is_active)
                        <div class="absolute bottom-1 right-1 w-5 h-5 bg-[#22c55e] border-[3px] border-white rounded-full shadow-[0_0_15px_rgba(34,197,94,0.8)]"></div>
                    @else
                        <div class="absolute bottom-1 right-1 w-5 h-5 bg-red-500 border-[3px] border-white rounded-full shadow-[0_0_15px_rgba(239,68,68,0.8)]"></div>
                    @endif
                </div>
                
                <h1 class="text-[22px] font-bold text-gray-900 tracking-tight text-center">
                    {{ $employee->full_name }}
                </h1>
                <p class="text-gray-700 font-medium mt-0.5 tracking-wide text-sm">{{ $employee->position }}</p>
                
                <div class="mt-4 px-4 py-1.5 rounded-full text-xs font-medium bg-white/40 backdrop-blur-sm border border-white/60 text-gray-800 shadow-[0_4px_15px_rgba(0,0,0,0.05)]">
                    {{ $employee->employee_code }}
                </div>
            </div>

            <!-- Content Section -->
            <div class="flex-1 flex flex-col gap-6 w-full z-20">
                
                <!-- Info Glass Card -->
                <div class="bg-white/40 backdrop-blur-xl rounded-[1.5rem] p-6 shadow-[0_8px_32px_rgba(0,0,0,0.08)] border border-white/60 relative overflow-hidden">
                    <!-- Shine Effect -->
                    <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-white to-transparent opacity-80"></div>
                    
                    <h3 class="text-[12px] font-semibold text-slate-500 uppercase tracking-widest mb-6">Informasi Pribadi</h3>
                    
                    <div class="space-y-6">
                        <!-- Phone -->
                        <div class="flex items-start gap-4">
                            <div class="mt-0.5 text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider mb-0.5">Nomor Telepon</p>
                                <p class="text-gray-900 font-medium text-[15px]">{{ $employee->phone }}</p>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="flex items-start gap-4">
                            <div class="mt-0.5 text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider mb-0.5">Alamat</p>
                                <p class="text-gray-900 font-medium text-[15px] leading-snug">{{ $employee->address }}</p>
                            </div>
                        </div>
                        
                        <!-- Join Date -->
                        <div class="flex items-start gap-4">
                            <div class="mt-0.5 text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider mb-0.5">Tanggal Bergabung</p>
                                <p class="text-gray-900 font-medium text-[15px]">{{ \Carbon\Carbon::parse($employee->join_date)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="mt-6 mb-8 w-full">
                    <button wire:click="logout" class="w-full flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold tracking-wide rounded-[1.25rem] shadow-[0_10px_25px_rgba(239,68,68,0.4)] border border-red-400/50 transition-all duration-300 hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        KELUAR DARI AKUN
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
