<div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="w-full sm:w-auto">
            <!-- Filter tabs -->
            <div class="flex flex-wrap gap-2">
                @foreach ([
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'all' => 'Semua',
                ] as $value => $label)
                    <button wire:click="$set('statusFilter', '{{ $value }}')"
                        class="px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ $statusFilter === $value ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/25' : 'bg-white text-gray-600 border border-gray-200 hover:bg-indigo-50' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl text-sm font-semibold text-amber-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ $pendingCount }} menunggu persetujuan
        </div>
    </div>

    <!-- Info banner (Admin tidak melihat halaman ini) -->
    <div class="mb-6 p-4 bg-indigo-50/80 border border-indigo-100 rounded-xl text-sm text-indigo-700">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Permintaan penghapusan data oleh Admin akan tampil di sini. Setelah Anda setujui, perubahan langsung dieksekusi.</span>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl shadow-indigo-200/20 border border-indigo-100/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-indigo-100">
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Jenis</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Subjek</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Diajukan Oleh</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-indigo-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $request)
                        @php
                            $payload = $request->payload ?? [];
                            $subject = match ($request->type) {
                                \App\Models\PendingChangeRequest::TYPE_DELETE_EMPLOYEE => ($payload['full_name'] ?? '-') . ' (' . ($payload['employee_code'] ?? '-') . ')',
                                \App\Models\PendingChangeRequest::TYPE_DELETE_OFFICE_LOCATION => $payload['name'] ?? '-',
                                \App\Models\PendingChangeRequest::TYPE_DELETE_ANNOUNCEMENT => $payload['title'] ?? '-',
                                default => '-',
                            };
                            $statusClass = match ($request->status) {
                                'approved' => 'bg-gradient-to-r from-green-400 to-emerald-500',
                                'rejected' => 'bg-gradient-to-r from-red-400 to-pink-500',
                                default => 'bg-gradient-to-r from-amber-400 to-orange-500',
                            };
                        @endphp
                        <tr class="hover:bg-gradient-to-r hover:from-indigo-50/50 hover:to-purple-50/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ $request->typeLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900">{{ $subject }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $request->requester?->username ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $request->created_at?->translatedFormat('d M Y, H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white shadow-lg {{ $statusClass }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($request->status === 'pending')
                                    <div class="flex justify-end space-x-2">
                                        <button wire:click="approve({{ $request->id }})"
                                            wire:confirm="Setujui dan eksekusi penghapusan ini?"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors duration-150">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Setujui
                                        </button>
                                        <button wire:click="reject({{ $request->id }})"
                                            wire:confirm="Tolak permintaan ini?"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-150">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Tolak
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">
                                        {{ $request->approver?->username ? 'oleh ' . $request->approver->username : '' }}
                                        {{ $request->approved_at ? ' • ' . $request->approved_at->translatedFormat('d M Y, H:i') : '' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada permintaan</h3>
                                    <p class="text-gray-500">Tidak ada permintaan dengan status ini.</p>
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
        {{ $requests->links() }}
    </div>
</div>