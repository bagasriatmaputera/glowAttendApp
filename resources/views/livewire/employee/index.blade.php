<div>
    <div class="flex justify-between items-center mb-6">
        <div class="w-1/3">
            <x-text-input wire:model.live.debounce.300ms="search" placeholder="Search employees..." class="w-full" />
        </div>
        <x-primary-button wire:click="create">
            {{ __('Add Employee') }}
        </x-primary-button>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Code</th>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Position</th>
                    <th scope="col" class="px-6 py-3">Phone</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $employee->employee_code }}</td>
                        <td class="px-6 py-4">{{ $employee->full_name }}</td>
                        <td class="px-6 py-4">{{ $employee->position }}</td>
                        <td class="px-6 py-4">{{ $employee->phone }}</td>
                        <td class="px-6 py-4">
                            @if($employee->is_active)
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button wire:click="edit({{ $employee->id }})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                            <button wire:click="confirmDelete({{ $employee->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center">No employees found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>

    <!-- Create/Edit Modal -->
    @if($showEmployeeModal)
    <x-modal name="employee-form" show="true" focusable>
        <form wire:submit="store" class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ $employee_id ? 'Edit Employee' : 'Create Employee' }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="user_id" value="Associated User" />
                    <select wire:model="user_id" id="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">Select a user</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="employee_code" value="Employee Code" />
                    <x-text-input wire:model="employee_code" id="employee_code" type="text" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('employee_code')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="full_name" value="Full Name" />
                    <x-text-input wire:model="full_name" id="full_name" type="text" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="phone" value="Phone" />
                    <x-text-input wire:model="phone" id="phone" type="text" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="position" value="Position" />
                    <x-text-input wire:model="position" id="position" type="text" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="address" value="Address" />
                    <textarea wire:model="address" id="address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3"></textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="join_date" value="Join Date" />
                    <x-text-input wire:model="join_date" id="join_date" type="date" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('join_date')" class="mt-2" />
                </div>

                <div class="flex items-center mt-4">
                    <input wire:model="is_active" id="is_active" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                    <label for="is_active" class="ms-2 text-sm text-gray-600 dark:text-gray-400">Active Employee</label>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button wire:click="closeModal" type="button">
                    Cancel
                </x-secondary-button>
                <x-primary-button class="ms-3">
                    Save
                </x-primary-button>
            </div>
        </form>
    </x-modal>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <x-modal name="delete-employee" show="true" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Are you sure you want to delete this employee?
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                This action cannot be undone. All related data might be affected.
            </p>
            <div class="mt-6 flex justify-end">
                <x-secondary-button wire:click="closeModal" type="button">
                    Cancel
                </x-secondary-button>
                <x-danger-button wire:click="delete" class="ms-3" type="button">
                    Delete
                </x-danger-button>
            </div>
        </div>
    </x-modal>
    @endif
</div>
