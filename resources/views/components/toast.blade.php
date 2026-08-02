<div
    x-data="{
        toasts: [],
        showToast(type, message) {
            const id = Date.now() + Math.random();
            this.toasts.push({ id, type, message });
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 4000);
        }
    }"
    @toast.window="showToast($event.detail.type ?? 'success', $event.detail.message)"
    class="fixed top-4 right-4 z-[200] w-[calc(100%-2rem)] max-w-sm space-y-2 pointer-events-none"
>
    <template x-for="t in toasts" :key="t.id">
        <div
            x-show="t"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto flex items-start gap-3 rounded-xl border p-4 shadow-lg backdrop-blur-md"
            :class="t.type === 'error' ? 'border-red-200 bg-red-50/95 text-red-800' : 'border-green-200 bg-green-50/95 text-green-800'"
        >
            <svg x-show="t.type === 'error'" class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <svg x-show="t.type !== 'error'" class="w-5 h-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="text-sm font-semibold" x-text="t.message"></p>
        </div>
    </template>
</div>
