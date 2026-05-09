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

        if(Auth()->user()->hasRole(['SuperAdmin', 'Admin'])){
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        } else {
            $this->redirectIntended(default: route('home', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-semibold text-gray-700 mb-2" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/80 backdrop-blur-sm transition-colors" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
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
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0" name="remember">
                <span class="ms-2 text-sm text-gray-700 font-medium">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-150" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Lupa kata sandi?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 rounded-xl transition-all duration-200 shadow-lg shadow-indigo-500/25">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
    </form>
</div>
