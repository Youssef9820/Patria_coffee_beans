<!-- SIDEBAR INNER CONTENT -->
<!-- Force Min-Height 100vh to ensure green background never ends -->
<div class="flex flex-col" style="background-color: #556B2F; min-height: 100vh;">
    
    <!-- 1. LOGO AREA -->
    <div class="h-16 flex items-center justify-center shrink-0" 
         style="background-color: #4B5F29; border-bottom: 1px solid #3E4F21;">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-mug-hot text-white text-xl"></i>
            <span class="text-lg font-bold brand-font tracking-wider text-white">PATRIA</span>
        </div>
    </div>

    <!-- 2. LINKS -->
    <nav class="flex-1 px-3 py-4 space-y-2">
        
        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-white whitespace-nowrap"
           style="{{ request()->routeIs('dashboard') ? 'background-color: #3E2723; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);' : '' }}">
            <i class="fa-solid fa-chart-pie w-5 text-center"></i>
            <span>Dashboard</span>
        </a>

        <!-- Green Coffee Inventory Link -->
        <!-- LOGIC: IF ACTIVE -> BROWN (#3E2723), ELSE -> TRANSPARENT -->
        <a href="{{ route('green-coffee.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-white whitespace-nowrap"
           style="{{ request()->routeIs('green-coffee.*') ? 'background-color: #3E2723; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);' : '' }}">
            <i class="fa-solid fa-seedling w-5 text-center"></i>
            <span>{{ __('messages.green_coffee_inventory') }}</span>
        </a>

    </nav>

</div>