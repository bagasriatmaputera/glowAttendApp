<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/95 backdrop-blur-sm border border-indigo-100/50 rounded-2xl shadow-xl shadow-indigo-200/20 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Karyawan</dt>
                            <dd class="text-lg font-semibold text-gray-900">24</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-sm border border-indigo-100/50 rounded-2xl shadow-xl shadow-indigo-200/20 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Hadir Hari Ini</dt>
                            <dd class="text-lg font-semibold text-gray-900">18</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-sm border border-indigo-100/50 rounded-2xl shadow-xl shadow-indigo-200/20 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Cuti Menunggu</dt>
                            <dd class="text-lg font-semibold text-gray-900">3</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-sm border border-indigo-100/50 rounded-2xl shadow-xl shadow-indigo-200/20 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-purple-400 to-pink-600 rounded-xl p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Jadwal</dt>
                            <dd class="text-lg font-semibold text-gray-900">12</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Section -->
        <div class="bg-white/95 backdrop-blur-sm border border-indigo-100/50 rounded-2xl shadow-xl shadow-indigo-200/20 overflow-hidden">
            <div class="p-8">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-indigo-400 to-purple-600 mb-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang di Glowttend!</h3>
                    <p class="text-gray-600 max-w-md mx-auto">
                        Sistem manajemen karyawan yang lengkap. Lacak kehadiran, kelola jadwal, dan tangani permintaan cuti semua dalam satu tempat.
                    </p>
                    <div class="mt-6 flex justify-center space-x-4">
                        <a href="{{ route('employees') }}" wire:navigate class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-500/25">
                            Kelola Karyawan
                        </a>
                        <a href="{{ route('attendance.index') }}" wire:navigate class="inline-flex items-center px-6 py-3 border border-indigo-300 text-base font-medium rounded-xl text-indigo-700 bg-white hover:bg-indigo-50 transition-colors duration-200">
                            Lihat Kehadiran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
