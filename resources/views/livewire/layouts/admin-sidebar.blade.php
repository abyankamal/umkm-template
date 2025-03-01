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
                            'children' => []
                        ],
                        [
                            'route' => 'admin.products',
                            'icon' => 'fas fa-box',
                            'label' => 'Produk',
                            'children' => [
                                [
                                    'route' => 'admin.products.list',
                                    'icon' => 'fas fa-list',
                                    'label' => 'Daftar Produk'
                                ],
                                [
                                    'route' => 'admin.products.categories',
                                    'icon' => 'fas fa-folder',
                                    'label' => 'Kategori Produk'
                                ],
                                [
                                    'route' => 'admin.products.variants',
                                    'icon' => 'fas fa-tags',
                                    'label' => 'Varian Produk'
                                ]
                            ]
                        ],
                        [
                            'route' => 'admin.articles',
                            'icon' => 'fas fa-newspaper',
                            'label' => 'Artikel',
                            'children' => [
                                [
                                    'route' => 'admin.articles.list',
                                    'icon' => 'fas fa-list',
                                    'label' => 'Daftar Artikel'
                                ],
                                [
                                    'route' => 'admin.articles.categories',
                                    'icon' => 'fas fa-folder',
                                    'label' => 'Kategori Artikel'
                                ],
                                [
                                    'route' => 'admin.articles.related-products',
                                    'icon' => 'fas fa-link',
                                    'label' => 'Produk Terkait'
                                ]
                            ]
                        ],
                        [
                            'route' => 'admin.orders',
                            'icon' => 'fas fa-shopping-cart',
                            'label' => 'Pesanan',
                            'children' => []
                        ],
                    ];
                @endphp

                @foreach ($menuItems as $item)
                    <li class="mb-2">
                        <div x-data="{ open: {{ request()->routeIs($item['route'] . '*') ? 'true' : 'false' }} }">
                            <a 
                                @if(empty($item['children']))
                                    wire:navigate 
                                    href="{{ route($item['route']) }}"
                                @else
                                    @click="open = !open"
                                @endif
                                class="flex items-center justify-between py-2 px-4 rounded-lg {{ request()->routeIs($item['route'] . '*') ? 'bg-kutamis-purple text-white' : 'text-gray-700 hover:bg-kutamis-purple hover:text-white' }}"
                            >
                                <div class="flex items-center">
                                    <i class="{{ $item['icon'] }} {{ request()->routeIs($item['route'] . '*') ? 'text-white' : 'text-gray-500 group-hover:text-white' }}"></i>
                                    <span class="ml-3">{{ $item['label'] }}</span>
                                </div>
                                @if(!empty($item['children']))
                                    <i class="fas" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                @endif
                            </a>
                            
                            @if(!empty($item['children']))
                                <div x-show="open" class="ml-4 mt-2 space-y-2">
                                    @foreach($item['children'] as $child)
                                        <a 
                                            wire:navigate 
                                            href="{{ route($child['route']) }}"
                                            class="flex items-center py-2 px-4 rounded-lg {{ request()->routeIs($child['route']) ? 'bg-kutamis-purple text-white' : 'text-gray-700 hover:bg-kutamis-purple hover:text-white' }}"
                                        >
                                            <i class="{{ $child['icon'] }} {{ request()->routeIs($child['route']) ? 'text-white' : 'text-gray-500 group-hover:text-white' }}"></i>
                                            <span class="ml-3">{{ $child['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</aside>