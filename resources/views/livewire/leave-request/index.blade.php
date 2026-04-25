<div>
    <div class="flex justify-between items-center mb-6 mt-4">
        <div class="w-1/3">
            <x-text-input wire:model.live.debounce.300ms="search" placeholder="Search by employee..." class="w-full" />
        </div>
        <x-primary-button>
            {{ __('New Request') }}
        </x-primary-button>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Employee</th>
                    <th scope="col" class="px-6 py-3">Type</th>
                    <th scope="col" class="px-6 py-3">Start Date</th>
                    <th scope="col" class="px-6 py-3">End Date</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaveRequests as $req)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $req->employee?->full_name }}</td>
                        <td class="px-6 py-4">{{ $req->leave_type }}</td>
                        <td class="px-6 py-4">{{ $req->start_date }}</td>
                        <td class="px-6 py-4">{{ $req->end_date }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full 
                                {{ $req->status === 'approved' ? 'text-green-700 bg-green-100' : ($req->status === 'rejected' ? 'text-red-700 bg-red-100' : 'text-yellow-700 bg-yellow-100') }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button class="font-medium text-blue-600 hover:underline">Review</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center">No leave requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $leaveRequests->links() }}
    </div>
</div>
