<div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="w-full sm:w-1/2 lg:w-1/3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <x-text-input wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan karyawan..." class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white/80 backdrop-blur-sm" />
            </div>
        </div>
        <button wire:click="create" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transform hover:scale-105">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Permintaan Cuti Baru') }}
            </span>
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl shadow-indigo-200/20 border border-indigo-100/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-indigo-100">
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Karyawan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Jenis Cuti</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Tanggal Mulai</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Tanggal Selesai</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-indigo-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($leaveRequests as $req)
                        <tr class="hover:bg-gradient-to-r hover:from-indigo-50/50 hover:to-purple-50/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                                            {{ substr($req->employee?->full_name ?? 'N/A', 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $req->employee?->full_name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @switch($req->leave_type)
                                    @case('sick')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-400 to-pink-500 text-white shadow-lg shadow-red-500/25">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                            </svg>
                                            Cuti Sakit
                                        </span>
                                        @break
                                    @case('annual')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-400 to-cyan-500 text-white shadow-lg shadow-blue-500/25">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 00-2 2v6a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-1a1 1 0 100-2h1a4 4 0 014 4v6a4 4 0 01-4 4H6a4 4 0 01-4-4V7a4 4 0 014-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Cuti Tahunan
                                        </span>
                                        @break
                                    @case('emergency')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-orange-400 to-red-500 text-white shadow-lg shadow-orange-500/25">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Cuti Darurat
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow-lg shadow-gray-500/25">
                                            {{ ucfirst($req->leave_type) }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $req->start_date }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $req->end_date }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @switch($req->status)
                                    @case('approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow-lg shadow-green-500/25">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Disetujui
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-400 to-pink-500 text-white shadow-lg shadow-red-500/25">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Ditolak
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-lg shadow-yellow-500/25">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Menunggu
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    @if($req->status === 'pending')
                                        <button wire:click="approve({{ $req->id }})" class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors duration-150">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Setujui
                                        </button>
                                        <button wire:click="reject({{ $req->id }})" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-150">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Tolak
                                        </button>
                                    @endif
                                    <button wire:click="edit({{ $req->id }})" class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1-9a2 2 0 012-2h2a2 2 0 012 2M9 9h6"></path>
                                        </svg>
                                        Ubah
                                    </button>
                                    <button wire:click="confirmDelete({{ $req->id }})" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-150">
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada permintaan cuti</h3>
                                    <p class="text-gray-500">Mulai dengan menambahkan permintaan cuti pertama.</p>
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
        {{ $leaveRequests->links() }}
    </div>

    <!-- Create/Edit Modal -->
    <div x-data="{ open: @entangle('showLeaveRequestModal') }" class="p-4" x-cloak>
    <template x-teleport="body">
        <!-- Wrapper -->
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
            
            <!-- Modal -->
            <div x-show="open" x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 scale-90" 
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 scale-100" 
                 x-transition:leave-end="opacity-0 scale-90"
                 class="relative z-10 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
                
                <!-- Header with Gradient -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6 border-b border-indigo-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-1">
                                {{ $leave_request_id ? 'Edit Permintaan Cuti' : 'Permintaan Cuti Baru' }}
                            </h2>
                            <p class="text-indigo-100">
                                {{ $leave_request_id ? 'Perbarui permintaan cuti' : 'Ajukan permintaan cuti baru' }}
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

                <!-- Form Content with Scroll -->
                <div class="p-8 overflow-y-auto max-h-[calc(90vh-200px)]">
                    <form wire:submit.prevent="store" class="space-y-8">
                        
                        <!-- Leave Request Information Section -->
                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-100">
                            <h3 class="text-lg font-semibold text-indigo-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 00-2 2v6a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-1a1 1 0 100-2h1a4 4 0 014 4v6a4 4 0 01-4 4H6a4 4 0 01-4-4V7a4 4 0 014-4z" clip-rule="evenodd"></path>
                                </svg>
                                Informasi Permintaan Cuti
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Employee Selection -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Karyawan <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="employee_id" 
                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                        <option value="">Pilih karyawan</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->full_name }} ({{ $employee->employee_code }})</option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Leave Type -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jenis Cuti <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="leave_type" 
                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                        <option value="sick">Cuti Sakit</option>
                                        <option value="annual">Cuti Tahunan</option>
                                        <option value="emergency">Cuti Darurat</option>
                                    </select>
                                    @error('leave_type')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Start Date -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Tanggal Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" wire:model="start_date" 
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                    @error('start_date')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Tanggal Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" wire:model="end_date" 
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                    @error('end_date')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Reason -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Alasan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea wire:model="reason" 
                                              placeholder="Jelaskan alasan permintaan cuti..."
                                              rows="4"
                                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors resize-none"></textarea>
                                    @error('reason')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Approval Status Section -->
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                            <h3 class="text-lg font-semibold text-purple-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Status Persetujuan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="status" 
                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                        <option value="pending">Menunggu</option>
                                        <option value="approved">Disetujui</option>
                                        <option value="rejected">Ditolak</option>
                                    </select>
                                    @error('status')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Approved By -->
                                @if($status !== 'pending')
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Disetujui Oleh
                                        </label>
                                        <select wire:model="approved_by" 
                                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors">
                                            <option value="">Pilih disetujui oleh</option>
                                            @foreach(\App\Models\User::all() as $user)
                                                <option value="{{ $user->id }}">{{ $user->username }}</option>
                                            @endforeach
                                        </select>
                                        @error('approved_by')
                                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
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
                                    {{ $leave_request_id ? 'Perbarui Permintaan' : 'Ajukan Permintaan' }}
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
        <!-- Wrapper -->
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
            
            <!-- Modal -->
            <div x-show="open" x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 scale-90" 
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 scale-100" 
                 x-transition:leave-end="opacity-0 scale-90"
                 class="relative z-10 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                
                <!-- Header with Gradient -->
                <div class="bg-gradient-to-r from-red-500 to-pink-600 px-8 py-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-white">Hapus Permintaan Cuti</h2>
                        <button wire:click="closeDeleteModal" 
                                class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-colors duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <!-- Warning Icon -->
                    <div class="flex items-center justify-center w-20 h-20 mx-auto bg-red-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>

                    <!-- Warning Message -->
                    <div class="text-center mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Apakah Anda benar-benar yakin?</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Tindakan ini tidak dapat dibatalkan. Ini akan menghapus permintaan cuti secara permanen dan semua data terkait.
                        </p>
                    </div>

                    <!-- Leave Request Info (if available) -->
                    @if($leave_request_id)
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <p class="text-sm text-gray-600 text-center">
                                Anda akan menghapus permintaan cuti dengan ID: <span class="font-semibold text-gray-900">#{{ $leave_request_id }}</span>
                            </p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
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
                                Hapus Permintaan Cuti
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
    </div>
</div>
