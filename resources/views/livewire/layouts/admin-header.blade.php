<header class="bg-white shadow sticky top-0 z-10">
    <div class="flex justify-between items-center px-6 py-4">

        @if(Auth::check())
            <div class="relative">
                <button wire:click="toggleProfileMenu" class="flex items-center space-x-2">
                    <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" class="w-8 h-8 rounded-full">
                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                </button>

                <!-- Profile Dropdown -->
                <div x-show="$wire.showProfileMenu"
                    x-transition
                    @click.away="$wire.showProfileMenu = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                    <a wire:navigate href="{{ route('admin.profile') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Edit Profil
                    </a>
                    <button wire:click="logout"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Keluar
                    </button>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
            <a href="{{ route('register') }}" class="ml-4 text-gray-700 hover:text-gray-900">Register</a>
        @endif
    </div>
</header>