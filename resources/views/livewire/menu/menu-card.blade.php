<ul class="m-4 grid grid-cols-3 gap-4 p-4">
    @foreach($menu as $item)
        @if(in_array($roleUser, $item['role']))
            <li>
                <a href="" {{-- INSTEAD {{ route($item['route']) }} --}}
                   class="flex flex-col items-center gap-2">
                    
                    {{-- Icon dengan border rounded --}}
                    <div class="p-[2px] rounded-2xl bg-gradient-to-r from-indigo-500 to-purple-600">
                        <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white">
                            {!! App\Support\Icons::get($item['icon'], 'w-6 h-6 text-indigo-500') !!}
                        </div>
                    </div>

                    {{-- Nama Menu --}}
                    <span class="text-xs text-gray-600 text-center">
                        {{ $item['nama'] }}
                    </span>

                </a>
            </li>
        @endif
    @endforeach
</ul>