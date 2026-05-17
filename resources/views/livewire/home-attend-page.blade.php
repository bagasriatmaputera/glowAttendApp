<x-app-layout>
    <div class="flex flex-col max-w-[480px] bg-white mx-auto shadow-lg min-h-screen">
        <div class="flex flex-col w-full justify-center items-center bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6 border-b border-indigo-100 rounded-b-3xl">
            <div class="flex w-full flex-row mb-2">
                <div class="text-start text-white font-semibold text-xl w-[80%]">
                    Welcome, <span class="font-bold">{{ auth()->user()->username }}</span>
                    <br>
                    Don't forget to record your attendance!
                </div>
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-white font-bold">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                    </svg>
                    <button class="text-white font-bold w-[20%] text-end"><i></i>Sign Out</button>
                </div>
            </div>
            <div class="flex flex-col w-full mx-2 bg-white rounded-lg p-4">
                <div class="text-start mb-2">
                    <i></i> <span class="font-bold">{{\Carbon\Carbon::now()->format('l')}}, {{ \Carbon\Carbon::now()->format('d F Y') }}</span>
                </div>
                <div class="flex justify-between gap-4 items-center">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-[2px] rounded-2xl inline-block hover:cursor-pointer hover:opacity-90 transition-opacity">
                        <div class="bg-white text-start font-bold p-3 rounded-[14px]">
                            Clock In
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-[2px] rounded-2xl inline-block hover:cursor-pointer hover:opacity-90 transition-opacity">
                        <div class="bg-white text-start font-bold p-3 rounded-[14px]">
                            Clock Out
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full p-2 bg-white ">
            <livewire:menu.menu-card />
        </div>
    </div>

    {{-- Navigation Bottom --}}
    <livewire:navigation.nav-bottom />
</x-app-layout>
