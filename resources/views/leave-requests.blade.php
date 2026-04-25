<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave Requests') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white/95 backdrop-blur-sm border border-indigo-100/50 rounded-2xl shadow-xl shadow-indigo-200/20 overflow-hidden">
            <div class="p-8 text-gray-900">
                <livewire:leave-request.index />
            </div>
        </div>
    </div>
</x-app-layout>
