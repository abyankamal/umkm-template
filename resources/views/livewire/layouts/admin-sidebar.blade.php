<aside class="w-64 bg-indigo-800 text-white">
    <div class="p-4">
        <div class="flex items-center justify-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12">
        </div>

        <nav class="space-y-2">
            <a wire:navigate href="{{ route('admin.dashboard') }}"
                class="flex items-center space-x-2 py-2 px-4 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-900' : 'hover:bg-indigo-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <span>Dashboard</span>
            </a>

            <a wire:navigate href="{{ route('admin.products') }}"
                class="flex items-center space-x-2 py-2 px-4 rounded-lg {{ request()->routeIs('admin.products') ? 'bg-indigo-900' : 'hover:bg-indigo-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span>Produk</span>
            </a>

            <a wire:navigate href="{{ route('admin.orders') }}"
                class="flex items-center space-x-2 py-2 px-4 rounded-lg {{ request()->routeIs('admin.orders') ? 'bg-indigo-900' : 'hover:bg-indigo-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span>Pesanan</span>
            </a>

            <a wire:navigate href="{{ route('admin.articles') }}"
                class="flex items-center space-x-2 py-2 px-4 rounded-lg {{ request()->routeIs('admin.articles') ? 'bg-indigo-900' : 'hover:bg-indigo-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <span>Artikel</span>
            </a>
        </nav>
    </div>
</aside>