<aside class="w-56 bg-[#1a3353] flex flex-col flex-shrink-0 min-h-screen">
    <div class="h-14 flex items-center px-5 border-b border-white/10">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white font-bold text-base tracking-wide">
            <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12 12 0 0112 21a12 12 0 01-6.16-3.422L12 14z"/>
            </svg>
            CUP — FICCT
        </a>
    </div>
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-5">
        <div>
            <p class="px-2 mb-1 text-[10px] font-semibold uppercase tracking-widest text-blue-300/60">Menú</p>
            <ul class="space-y-0.5">
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('profile.edit') ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Perfil
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="px-4 py-3 border-t border-white/10">
        <p class="text-[10px] text-blue-300/40 text-center">UAGRM © {{ date('Y') }}</p>
    </div>
</aside>
