<aside class="w-64 bg-white text-kutamis-purple">
    <div class="p-4">
        <div class="flex items-center justify-center mb-8">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12">
        </div>

        <nav class="space-y-2 overflow-y-auto">
            <ul>
                @php
                    $menuItems = [
                        [
                            'route' => 'admin.dashboard',
                            'icon' => 'fas fa-tachometer-alt',
                            'label' => 'Dashboard',
                        ],
                        [
                            'route' => 'admin.products',
                            'icon' => 'fas fa-box',
                            'label' => 'Produk',
                        ],
                        [
                            'route' => 'admin.orders',
                            'icon' => 'fas fa-shopping-cart',
                            'label' => 'Pesanan',
                        ],
                        [
                            'route' => 'admin.articles',
                            'icon' => 'fas fa-newspaper',
                            'label' => 'Artikel',
                        ],
                    ];
                @endphp

                @foreach ($menuItems as $item)
                    <li class="mb-2">
                        <a wire:navigate href="{{ route($item['route']) }}" class="flex items-center space-x-2 py-2 px-4 rounded-lg {{ request()->routeIs($item['route']) ? 'bg-kutamis-purple text-white' : 'text-gray-700 hover:bg-kutamis-purple hover:text-white' }}">
                            <i class="{{ $item['icon'] }} {{ request()->routeIs($item['route']) ? 'text-white' : 'text-gray-500 hover:text-white' }}"></i>
                            <span class="ml-3">{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</aside>