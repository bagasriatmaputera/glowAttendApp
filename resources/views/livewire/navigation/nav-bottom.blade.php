<div>
    <nav
        class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white border-t border-gray-100 shadow-lg z-50">
        <ul class="flex items-center justify-around px-4 py-2">
            @foreach ($menu as $item)
                <li class="flex-1">
                    @php
                        $routeExists = \Illuminate\Support\Facades\Route::has($item['route']);
                        $isActive = $routeExists && request()->routeIs($item['route']);
                    @endphp

                    @if ($routeExists)
                        <a href="{{ route($item['route']) }}" class="flex flex-col items-center gap-1 py-1">

                            {{-- Icon --}}
                            <div @class([
                                'p-[2px] rounded-xl transition-all duration-200',
                                'bg-gradient-to-r from-indigo-500 to-purple-600' => $isActive,
                                'bg-transparent' => !$isActive,
                            ])>
                                <div @class([
                                    'w-10 h-10 flex items-center justify-center rounded-xl transition-all duration-200',
                                    'bg-white' => $isActive,
                                    'bg-transparent' => !$isActive,
                                ])>
                                    {!! App\Support\Icons::get($item['icon'], 'w-5 h-5 ' . ($isActive ? 'text-indigo-500' : 'text-gray-400')) !!}
                                </div>
                            </div>

                            {{-- Nama --}}
                            <span @class([
                                'text-xs font-medium transition-all duration-200',
                                'text-indigo-500' => $isActive,
                                'text-gray-400' => !$isActive,
                            ])>
                                {{ $item['nama'] }}
                            </span>

                        </a>
                    @else
                        {{-- Coming Soon --}}
                        <button onclick="alert('{{ $item['nama'] }} segera hadir!')"
                            class="flex flex-col items-center gap-1 py-1 w-full">
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl">
                                {!! App\Support\Icons::get($item['icon'], 'w-5 h-5 text-gray-300') !!}
                            </div>
                            <span class="text-xs font-medium text-gray-300">
                                {{ $item['nama'] }}
                            </span>
                        </button>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
    <div class="h-20"></div>
</div>
