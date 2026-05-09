<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex items-center justify-center p-4">
    <div
        class="w-full max-w-[480px] bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-indigo-100/50 overflow-hidden">
        <!-- Header with Gradient -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 border-b border-indigo-100">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">
                        Permintaan Cuti Baru
                    </h2>
                    <p class="text-indigo-100 text-sm">
                        Ajukan permintaan cuti baru
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(100vh-200px)]">
            <form wire:submit.prevent="store" class="space-y-6">

                <!-- Leave Request Information Section -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-100">
                    <h3 class="text-base font-semibold text-indigo-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 00-2 2v6a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-1a1 1 0 100-2h1a4 4 0 014 4v6a4 4 0 01-4 4H6a4 4 0 01-4-4V7a4 4 0 014-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Informasi Permintaan Cuti
                    </h3>
                    <div class="space-y-4">
                        <!-- Employee Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Karyawan <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="employee_id"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors text-sm">
                                <option value="">Pilih karyawan</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->full_name }}
                                        ({{ $employee->employee_code }})</option>
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
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors text-sm">
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
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors text-sm">
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
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors text-sm">
                            @error('end_date')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Reason -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alasan <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="reason" placeholder="Jelaskan alasan permintaan cuti..." rows="3"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors resize-none text-sm"></textarea>
                            @error('reason')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Approval Status Section -->
                

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" wire:click="$this->redirect(route('leave-requests.index'), navigate: true)"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition-colors duration-150 text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:from-indigo-600 hover:to-purple-700 font-semibold transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transform hover:scale-105 text-sm">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Ajukan Permintaan
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
