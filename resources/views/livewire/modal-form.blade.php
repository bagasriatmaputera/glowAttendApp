<div>
    {{-- Tombol trigger --}}
    <button wire:click="openModal"
        class="px-4 py-2 bg-purple-600 text-white rounded-lg">
        Buka Modal
    </button>

    {{-- Modal Overlay --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">

            {{-- Background gelap --}}
            <div class="absolute inset-0 bg-black/50"
                wire:click="closeModal"></div>

            {{-- Kotak Modal --}}
            <div class="relative z-10 bg-white rounded-xl shadow-xl w-full max-w-md p-6">

                {{-- Header --}}
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Judul Modal</h2>
                    <button wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                {{-- Isi Modal --}}
                <div class="mb-6">
                    <p>Isi konten modal di sini...</p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2">
                    <button wire:click="closeModal"
                        class="px-4 py-2 border rounded-lg text-gray-600">
                        Batal
                    </button>
                    <button wire:click="simpan"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg">
                        Simpan
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>