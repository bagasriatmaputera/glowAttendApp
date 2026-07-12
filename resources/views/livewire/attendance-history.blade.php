<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl shadow-indigo-200/20 border border-indigo-100/50 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h2 class="text-xl font-bold text-gray-800">Riwayat Kehadiran Anda</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Clock In</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Clock Out</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $attendance->date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($attendance->status == 'present')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Hadir</span>
                                @elseif($attendance->status == 'late')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Terlambat</span>
                                @elseif($attendance->status == 'absent')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Absen</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Cuti</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada data kehadiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
