<?php

use App\Livewire\Forms\LoginForm;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;
    public ?string $selectedRole = null;

    /**
     * Pre-fill credentials for demo accounts based on role.
     */
    public function selectRole(string $role, bool $autoSubmit = false): void
    {
        $this->selectedRole = $role;
        $this->resetErrorBag();

        $accounts = [
            'management' => [
                'login' => 'owner@glow.com',
                'password' => 'password',
            ],
            'admin' => [
                'login' => 'management@glow.com',
                'password' => 'password',
            ],
            'employee' => [
                'login' => 'employee@glow.com',
                'password' => 'password',
            ],
        ];

        // Attempt dynamic resolution from database if available
        try {
            if ($role === 'management') {
                $user = User::role('Management')->first()
                    ?? User::where('email', 'owner@glow.com')->first()
                    ?? User::where('email', 'management@example.com')->first();
                if ($user) {
                    $accounts['management']['login'] = $user->email ?? $user->username;
                }
            } elseif ($role === 'admin') {
                $user = User::role('Admin')->first()
                    ?? User::where('email', 'management@glow.com')->first()
                    ?? User::where('email', 'admin@example.com')->first();
                if ($user) {
                    $accounts['admin']['login'] = $user->email ?? $user->username;
                }
            } elseif ($role === 'employee') {
                $user = User::role('Employee')->first()
                    ?? User::where('email', 'employee@glow.com')->first()
                    ?? User::where('email', 'employee@example.com')->first();
                if ($user) {
                    $accounts['employee']['login'] = $user->email ?? $user->username;
                }
            }
        } catch (\Throwable $e) {
            // Use defaults
        }

        if (isset($accounts[$role])) {
            $this->form->login = $accounts[$role]['login'];
            $this->form->password = $accounts[$role]['password'];
        }

        if ($autoSubmit) {
            $this->login();
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (auth()->user()->hasRole(['Management', 'Admin'])) {
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        } else {
            // Ensure Employee has an employee record in demo mode
            if (!auth()->user()->employee) {
                Employee::firstOrCreate(
                    ['user_id' => auth()->id()],
                    [
                        'employee_code' => 'EMP-' . str_pad((string)auth()->id(), 3, '0', STR_PAD_LEFT),
                        'full_name' => auth()->user()->username ?? 'Demo Employee',
                        'phone' => '081234567890',
                        'address' => 'Jakarta, Indonesia',
                        'position' => 'Stylist',
                        'join_date' => now()->subMonths(3)->toDateString(),
                        'is_active' => true,
                    ]
                );
            }
            $this->redirect(route('home', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
    <!-- Welcome -->
    <div class="mb-5 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang</h2>
        <p class="mt-1 text-sm text-gray-500">Pilih role demo atau masukkan akun Anda untuk melanjutkan.</p>
    </div>

    <!-- Quick Role Selector for Demo -->
    <div class="mb-6 p-3.5 bg-gradient-to-br from-slate-50 to-indigo-50/30 rounded-2xl border border-indigo-100/70 shadow-sm">
        <div class="flex items-center justify-between mb-2.5 px-1">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-600 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Pilih Akun Demo (Per Role)
            </span>
            <span class="text-[10px] font-semibold text-indigo-700 bg-indigo-100/70 px-2 py-0.5 rounded-full">
                1-Click Select
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
            <!-- Owner / Management -->
            <button type="button" 
                    wire:click="selectRole('management')"
                    class="group relative flex flex-col text-left p-2.5 rounded-xl border transition-all duration-200 {{ $selectedRole === 'management' ? 'border-amber-500 bg-amber-50 ring-2 ring-amber-400/30 shadow-sm' : 'border-gray-200/80 bg-white hover:border-amber-300 hover:bg-amber-50/40' }}">
                <div class="flex items-center justify-between w-full mb-1">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500 {{ $selectedRole === 'management' ? 'animate-pulse' : '' }}"></span>
                        <span class="text-xs font-bold text-gray-900 group-hover:text-amber-700">Owner</span>
                    </div>
                    <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded bg-amber-100 text-amber-800 uppercase tracking-wider">Owner</span>
                </div>
                <span class="text-[11px] font-mono text-gray-600 truncate w-full">owner@glow.com</span>
                <span class="text-[10px] text-gray-400 mt-1 leading-tight">Full & Approvals</span>
            </button>

            <!-- Admin -->
            <button type="button" 
                    wire:click="selectRole('admin')"
                    class="group relative flex flex-col text-left p-2.5 rounded-xl border transition-all duration-200 {{ $selectedRole === 'admin' ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-400/30 shadow-sm' : 'border-gray-200/80 bg-white hover:border-indigo-300 hover:bg-indigo-50/40' }}">
                <div class="flex items-center justify-between w-full mb-1">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 {{ $selectedRole === 'admin' ? 'animate-pulse' : '' }}"></span>
                        <span class="text-xs font-bold text-gray-900 group-hover:text-indigo-700">Admin</span>
                    </div>
                    <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded bg-indigo-100 text-indigo-800 uppercase tracking-wider">Admin</span>
                </div>
                <span class="text-[11px] font-mono text-gray-600 truncate w-full">management@glow.com</span>
                <span class="text-[10px] text-gray-400 mt-1 leading-tight">Kelola Presensi & HR</span>
            </button>

            <!-- Employee -->
            <button type="button" 
                    wire:click="selectRole('employee')"
                    class="group relative flex flex-col text-left p-2.5 rounded-xl border transition-all duration-200 {{ $selectedRole === 'employee' ? 'border-emerald-500 bg-emerald-50 ring-2 ring-emerald-400/30 shadow-sm' : 'border-gray-200/80 bg-white hover:border-emerald-300 hover:bg-emerald-50/40' }}">
                <div class="flex items-center justify-between w-full mb-1">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 {{ $selectedRole === 'employee' ? 'animate-pulse' : '' }}"></span>
                        <span class="text-xs font-bold text-gray-900 group-hover:text-emerald-700">Karyawan</span>
                    </div>
                    <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800 uppercase tracking-wider">Staff</span>
                </div>
                <span class="text-[11px] font-mono text-gray-600 truncate w-full">employee@glow.com</span>
                <span class="text-[10px] text-gray-400 mt-1 leading-tight">Mobile Presensi</span>
            </button>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
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
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0" name="remember">
                <span class="ms-2 text-sm text-gray-700 font-medium">{{ __('Ingat saya') }}</span>
            </label>

            <span class="text-xs text-gray-400 font-mono">Password demo: <span class="font-semibold text-gray-600">password</span></span>
        </div>

        <div class="mt-6 flex flex-col gap-2">
            <x-primary-button class="w-full justify-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 rounded-xl transition-all duration-200 shadow-lg shadow-indigo-500/25">
                <span wire:loading.remove wire:target="login, selectRole">
                    @if ($selectedRole === 'management')
                        {{ __('Masuk sebagai Owner') }}
                    @elseif ($selectedRole === 'admin')
                        {{ __('Masuk sebagai Admin') }}
                    @elseif ($selectedRole === 'employee')
                        {{ __('Masuk sebagai Karyawan') }}
                    @else
                        {{ __('Masuk') }}
                    @endif
                </span>
                <span wire:loading wire:target="login, selectRole" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('Memproses...') }}
                </span>
            </x-primary-button>
        </div>
    </form>
</div>
