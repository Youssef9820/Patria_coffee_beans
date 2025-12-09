<header class="bg-white h-16 border-b border-gray-200 shadow-sm flex items-center justify-between px-6 z-10 shrink-0">
    
    <!-- LEFT: TOGGLE SIDEBAR -->
    <button @click="sidebarOpen = !sidebarOpen" 
            class="text-[#556B2F] hover:bg-[#F5F5DC] p-2 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-[#556B2F]">
        <i class="fa-solid fa-bars fa-lg"></i>
    </button>

    <!-- RIGHT: USER DROPDOWN -->
    <div class="flex items-center">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[#3E2723] bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs" style="background-color: #3E2723;">
                            A
                        </div>
                        <div class="font-semibold">{{ Auth::user()->name }}</div>
                    </div>

                    <div class="ms-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>

</header>