<div>
    <div class="flex justify-between items-center mb-6 mt-4">
        <div class="w-1/3">
            <x-text-input wire:model.live.debounce.300ms="search" placeholder="Search by employee..." class="w-full" />
        </div>
        <x-primary-button>
            {{ __('Assign Schedule') }}
        </x-primary-button>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Employee</th>
                    <th scope="col" class="px-6 py-3">Day of Week</th>
                    <th scope="col" class="px-6 py-3">Schedule Type</th>
                    <th scope="col" class="px-6 py-3">Shift Time</th>
                    <th scope="col" class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $sched)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $sched->employee?->full_name }}</td>
                        <td class="px-6 py-4">{{ $sched->day_of_week }}</td>
                        <td class="px-6 py-4">{{ $sched->schedule?->name }}</td>
                        <td class="px-6 py-4">{{ $sched->schedule?->start_time }} - {{ $sched->schedule?->end_time }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button class="font-medium text-blue-600 hover:underline">Edit</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center">No schedules assigned.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $schedules->links() }}
    </div>
</div>
