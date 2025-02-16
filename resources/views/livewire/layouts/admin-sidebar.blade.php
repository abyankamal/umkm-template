<aside class="w-64 bg-white text-kutamis-purple">
    <div class="p-4">
        <div class="flex items-center justify-center mb-8">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12">
        </div>

        <nav class="space-y-2">
            <ul>
                <li class="mb-2">
                    <a wire:navigate href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 py-2 px-4 rounded-lg text-gray-700 hover:bg-kutamis-purple hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-kutamis-purple text-white' : '' }}">
                        <i class="fas fa-tachometer-alt {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-500' }}"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a wire:navigate href="{{ route('admin.products') }}" class="flex items-center space-x-2 py-2 px-4 rounded-lg text-gray-700 hover:bg-kutamis-purple hover:text-white {{ request()->routeIs('admin.products') ? 'bg-kutamis-purple text-white' : '' }}">
                        <i class="fas fa-box {{ request()->routeIs('admin.products') ? 'text-white' : 'text-gray-500' }}"></i>
                        <span class="ml-3">Produk</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a wire:navigate href="{{ route('admin.orders') }}" class="flex items-center space-x-2 py-2 px-4 rounded-lg text-gray-700 hover:bg-kutamis-purple hover:text-white {{ request()->routeIs('admin.orders') ? 'bg-kutamis-purple text-white' : '' }}">
                        <i class="fas fa-shopping-cart {{ request()->routeIs('admin.orders') ? 'text-white' : 'text-gray-500' }}"></i>
                        <span class="ml-3">Pesanan</span>
                    </a>
                </li>
                <li class="mb-2 hover:text-white">
                    <a wire:navigate href="{{ route('admin.articles') }}" class="flex items-center space-x-2 py-2 px-4 rounded-lg text-gray-700 hover:bg-kutamis-purple hover:text-white {{ request()->routeIs('admin.articles') ? 'bg-kutamis-purple text-white' : '' }}">
                        <i class="fas fa-newspaper hover:text-white {{ request()->routeIs('admin.articles') ? 'text-white' : 'text-gray-500' }}"></i>
                        <span class="ml-3">Artikel</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>