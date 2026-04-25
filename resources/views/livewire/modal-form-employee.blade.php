<div x-data="{ open: @entangle('showEmployeeModal') }">

    {{-- Wrapper --}}
    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center">

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/50"
             wire:click="closeModal"></div>

        {{-- Modal --}}
        <div
            x-show="open"
            x-transition
            class="relative z-10 bg-white rounded-xl shadow-xl w-full max-w-2xl p-6">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">
                    {{ $employee_id ? 'Edit Employee' : 'Create Employee' }}
                </h2>
                <button wire:click="closeModal"
                    class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="store">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- User --}}
                    <div>
                        <label class="text-sm">User</label>
                        <select wire:model="user_id" class="w-full border rounded-lg p-2">
                            <option value="">Select user</option>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->username }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Code --}}
                    <div>
                        <label class="text-sm">Employee Code</label>
                        <input type="text" wire:model="employee_code" class="w-full border rounded-lg p-2">
                        @error('employee_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Name --}}
                    <div class="md:col-span-2">
                        <label class="text-sm">Full Name</label>
                        <input type="text" wire:model="full_name" class="w-full border rounded-lg p-2">
                        @error('full_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="text-sm">Phone</label>
                        <input type="text" wire:model="phone" class="w-full border rounded-lg p-2">
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Position --}}
                    <div>
                        <label class="text-sm">Position</label>
                        <input type="text" wire:model="position" class="w-full border rounded-lg p-2">
                        @error('position') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Address --}}
                    <div class="md:col-span-2">
                        <label class="text-sm">Address</label>
                        <textarea wire:model="address" class="w-full border rounded-lg p-2"></textarea>
                        @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Join Date --}}
                    <div>
                        <label class="text-sm">Join Date</label>
                        <input type="date" wire:model="join_date" class="w-full border rounded-lg p-2">
                        @error('join_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center mt-6">
                        <input type="checkbox" wire:model="is_active" class="mr-2">
                        <label>Active</label>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 border rounded-lg">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg">
                        {{ $employee_id ? 'Update' : 'Save' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>