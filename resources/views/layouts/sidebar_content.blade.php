<div class="flex flex-col h-full bg-[#6B7F3B]">
    
    <!-- LOGO AREA -->
    <div class="h-16 flex items-center justify-center bg-[#6B7F3B] border-b border-[#556B2F] shrink-0">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-mug-hot text-white text-2xl"></i>
            <span class="text-xl font-bold brand-font tracking-wider text-white">PATRIA</span>
        </div>
    </div>

    <!-- NAVIGATION LINKS -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-md text-sm font-medium transition-colors text-white
           {{ request()->routeIs('dashboard') ? 'bg-[#556B2F]' : 'hover:bg-[#556B2F]' }}">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        <!-- Green Coffee Inventory -->
        <a href="{{ route('green-coffee.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-md text-sm font-medium transition-colors text-white
           {{ request()->routeIs('green-coffee.*') ? 'bg-[#3E2723]' : 'hover:bg-[#556B2F]' }}">
            <i class="fa-solid fa-seedling"></i>
            <span>{{ __('messages.green_coffee_inventory') }}</span>
        </a>

    </nav>

    <!-- USER SECTION AT BOTTOM -->
    <div class="border-t border-[#556B2F] px-3 py-4 shrink-0">
        <div class="flex items-center gap-3 px-3 py-2 mb-2">
            <div class="w-9 h-9 rounded-full bg-[#3E2723] flex items-center justify-center text-white font-bold text-sm">
                A
            </div>
            <div class="flex-1 text-white">
                <div class="font-semibold text-sm">Admin</div>
                <div class="text-xs text-white/80">Admin</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-2.5 w-full rounded-md hover:bg-[#556B2F] transition-all text-white text-sm">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="font-medium">Logout</span>
            </button>
        </form>
    </div>

</div>