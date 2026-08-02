<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if(Auth()->user()->hasRole(['Management', 'Admin'])){
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        } else {
            // Ensure Employee has an employee record
            if (!Auth()->user()->employee) {
                auth()->logout();
                abort(403, 'Akses Ditolak: Akun Anda belum terdaftar sebagai Karyawan aktif. Silakan hubungi Administrator.');
            }
            $this->redirect(route('home', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
    <!-- Welcome -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang</h2>
        <p class="mt-1 text-sm text-gray-500">Silakan masuk dengan email atau username Anda untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email / Username -->
        <div>
            <x-input-label for="login" :value="__('Email / Username')" class="text-sm font-semibold text-gray-700 mb-2" />
            <x-text-input wire:model="form.login" id="login" class="block mt-1 w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors" type="text" name="login" placeholder="Masukkan email atau username" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Kata Sandi')" class="text-sm font-semibold text-gray-700 mb-2" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0" name="remember">
                <span class="ms-2 text-sm text-gray-700 font-medium">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 rounded-xl transition-all duration-200 shadow-lg shadow-indigo-500/25">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
    </form>
</div>
