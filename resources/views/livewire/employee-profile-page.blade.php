<div class="min-h-screen bg-gray-50 flex flex-col font-sans pb-24 relative">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#d95c37] to-[#2b3058] pt-12 pb-8 px-6 rounded-b-[2rem] shadow-sm text-white relative">
        <div class="max-w-md mx-auto flex flex-col items-center text-center">
            <!-- Avatar with border and status indicator -->
            <div class="relative mb-4">
                <div class="w-24 h-24 rounded-full border-[3px] border-white/30 bg-violet-600 flex items-center justify-center text-white font-medium text-3xl overflow-hidden shadow-lg">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->full_name) }}&background=6d28d9&color=fff&size=128&bold=true" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <!-- Status Badge -->
                <div class="absolute bottom-1 right-1 w-5 h-5 {{ $employee->is_active ? 'bg-green-500 shadow-green-500/50' : 'bg-red-500 shadow-red-500/50' }} border-[3px] border-white rounded-full shadow-md"></div>
            </div>

            <h1 class="text-xl font-extrabold tracking-tight">{{ $employee->full_name }}</h1>
            <p class="text-xs text-orange-200 font-bold uppercase tracking-wider mt-0.5">{{ $employee->position }}</p>
            
            <span class="mt-3 px-3.5 py-1 bg-white/10 backdrop-blur-md rounded-full text-[10px] font-bold tracking-widest border border-white/10">
                {{ $employee->employee_code }}
            </span>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-md mx-auto w-full px-5 mt-6 flex-1 flex flex-col gap-5">
        
        @if (session()->has('message'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-2xl flex items-center shadow-sm">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-bold text-xs">{{ session('message') }}</span>
            </div>
        @endif

        <!-- Card: Personal Info -->
        <div class="bg-white rounded-2xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-gray-100/80 flex flex-col gap-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-2">Informasi Pribadi</h3>
            
            <!-- Email -->
            <div class="flex items-center gap-3.5">
                <div class="p-2 bg-indigo-50 text-[#6366f1] rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $employee->user->email ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Phone -->
            <div class="flex items-center gap-3.5">
                <div class="p-2 bg-indigo-50 text-[#6366f1] rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">No. Handphone</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $employee->phone ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Address -->
            <div class="flex items-start gap-3.5">
                <div class="p-2 bg-indigo-50 text-[#6366f1] rounded-xl mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Alamat Domisili</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5 leading-snug">{{ $employee->address ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Date of Birth (Placeholder since not in DB) -->
            <div class="flex items-center gap-3.5">
                <div class="p-2 bg-indigo-50 text-[#6366f1] rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal Lahir</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5">18 Agustus 1998</p>
                </div>
            </div>
        </div>

        <!-- Card: Employment Info -->
        <div class="bg-white rounded-2xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-gray-100/80 flex flex-col gap-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-2">Detail Pekerjaan</h3>
            
            <!-- Employee ID -->
            <div class="flex items-center gap-3.5">
                <div class="p-2 bg-orange-50 text-orange-500 rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M21 12h-4m4 4h-4m4-8h-4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nomor Induk Karyawan (NIK)</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $employee->employee_code }}</p>
                </div>
            </div>

            <!-- Dept -->
            <div class="flex items-center gap-3.5">
                <div class="p-2 bg-orange-50 text-orange-500 rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Departemen / Divisi</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $employee->position ?? '-' }}</p>
                </div>
            </div>

            <!-- Join Date -->
            <div class="flex items-center gap-3.5">
                <div class="p-2 bg-orange-50 text-orange-500 rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal Bergabung</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ \Carbon\Carbon::parse($employee->join_date)->translatedFormat('d F Y') }}</p>
                </div>
            </div>

            <!-- Manager (SuperAdmin/Owner) -->
            <div class="flex items-center gap-3.5">
                <div class="p-2 bg-orange-50 text-orange-500 rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Atasan (Manager)</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5">Owner</p>
                </div>
            </div>
        </div>

        <!-- Edit Profile & Logout Buttons -->
        <div class="flex flex-col gap-3 mt-2">
            <button wire:click="openEditModal" class="w-full py-3.5 bg-white text-[#6366f1] border border-indigo-100 hover:bg-indigo-50 font-bold rounded-xl shadow-sm text-sm transition-all flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit Profil Anda
            </button>

            <button wire:click="openPasswordModal" class="w-full py-3.5 bg-white text-[#6366f1] border border-indigo-100 hover:bg-indigo-50 font-bold rounded-xl shadow-sm text-sm transition-all flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                Ubah Kata Sandi
            </button>

            <button wire:click="logout" class="w-full py-3.5 bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 font-bold rounded-xl text-sm transition-all flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar dari Akun
            </button>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div x-data="{ open: @entangle('showEditModal') }" x-cloak>
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Modal Overlay -->
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" wire:click="closeEditModal" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <!-- Modal Content -->
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95" class="relative bg-white w-full max-w-md rounded-2xl shadow-2xl z-10 overflow-hidden flex flex-col max-h-[75vh]">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-bold">Edit Profil</h3>
                        <p class="text-[11px] text-indigo-100">Perbarui nomor HP dan alamat Anda</p>
                    </div>
                    <button wire:click="closeEditModal" class="p-1 hover:bg-white/10 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Body (scrollable fields) -->
                <div class="flex-1 p-5 overflow-y-auto space-y-4">
                    <div class="space-y-4">
                        <!-- Phone Input -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">No. Handphone <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="phone" placeholder="Masukkan nomor handphone..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6366f1] focus:border-transparent bg-white text-xs font-semibold text-gray-700">
                            @error('phone')
                                <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Address Input -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Alamat Domisili <span class="text-red-500">*</span></label>
                            <textarea wire:model="address" placeholder="Masukkan alamat lengkap domisili..." rows="4" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6366f1] focus:border-transparent bg-white text-xs font-medium text-gray-700 resize-none"></textarea>
                            @error('address')
                                <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (always visible) -->
                <div class="px-5 py-4 border-t border-gray-200 bg-white flex gap-3 shrink-0">
                    <button type="button" wire:click="closeEditModal" class="flex-1 py-2.5 border border-gray-300 text-gray-600 rounded-xl font-bold text-xs hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button wire:click="saveProfile" class="flex-1 py-2.5 bg-[#6366f1] text-white rounded-xl font-bold text-xs hover:bg-[#5355d1] transition-colors shadow-lg shadow-indigo-500/20">
                        Simpan Profil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div x-data="{ open: @entangle('showPasswordModal') }" x-cloak>
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" wire:click="closePasswordModal" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95" class="relative bg-white w-full max-w-md rounded-2xl shadow-2xl z-10 overflow-hidden flex flex-col max-h-[75vh]">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-bold">Ubah Kata Sandi</h3>
                        <p class="text-[11px] text-indigo-100">Masukkan kata sandi lama terlebih dahulu</p>
                    </div>
                    <button wire:click="closePasswordModal" class="p-1 hover:bg-white/10 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit="updatePassword" class="flex-1 p-5 overflow-y-auto space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kata Sandi Lama <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="current_password" placeholder="Masukkan kata sandi lama..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6366f1] focus:border-transparent bg-white text-xs font-semibold text-gray-700">
                        @error('current_password')
                            <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kata Sandi Baru <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="password" placeholder="Masukkan kata sandi baru..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6366f1] focus:border-transparent bg-white text-xs font-semibold text-gray-700">
                        @error('password')
                            <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="password_confirmation" placeholder="Ulangi kata sandi baru..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#6366f1] focus:border-transparent bg-white text-xs font-semibold text-gray-700">
                        @error('password_confirmation')
                            <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-3 shrink-0 pt-2">
                        <button type="button" wire:click="closePasswordModal" class="flex-1 py-2.5 border border-gray-300 text-gray-600 rounded-xl font-bold text-xs hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 bg-[#6366f1] text-white rounded-xl font-bold text-xs hover:bg-[#5355d1] transition-colors shadow-lg shadow-indigo-500/20">
                            Simpan Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 z-50">
        <livewire:navigation.nav-bottom />
    </div>
</div>
