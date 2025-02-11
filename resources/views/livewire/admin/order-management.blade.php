<div>
    <div class="p-4 bg-white rounded-lg shadow-xs">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-semibold">Manajemen Pesanan</h2>
        </div>

        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="mb-4 flex space-x-4">
            <!-- Search Input -->
            <div class="flex-grow">
                <input 
                    wire:model.live="searchTerm" 
                    type="text" 
                    placeholder="Cari pesanan berdasarkan nama, email, atau ID" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                >
            </div>

            <!-- Status Filter -->
            <div>
                <select 
                    wire:model.live="selectedStatus" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                >
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                        <th class="px-4 py-3">ID Pesanan</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @foreach($orders as $order)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3">#{{ $order->id }}</td>
                            <td class="px-4 py-3">{{ $order->user->name }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="
                                    px-2 py-1 rounded text-xs 
                                    {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <div x-data="{ open: false }" class="relative">
                                        <button 
                                            @click="open = !open" 
                                            class="px-3 py-1 text-sm font-medium text-blue-600 hover:text-blue-700"
                                        >
                                            Ubah Status
                                        </button>
                                        <div 
                                            x-show="open" 
                                            @click.away="open = false"
                                            class="absolute z-10 right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg"
                                        >
                                            @foreach($statuses as $status)
                                                @if($status !== $order->status)
                                                    <button 
                                                        wire:click="updateOrderStatus({{ $order->id }}, '{{ $status }}')"
                                                        class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
                                                    >
                                                        {{ ucfirst($status) }}
                                                    </button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <button 
                                        wire:click="viewOrderDetails({{ $order->id }})"
                                        class="px-3 py-1 text-sm font-medium text-green-600 hover:text-green-700"
                                    >
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Order Details Modal -->
    <div 
        x-data="{ showModal: false, orderDetails: null }"
        x-on:show-order-details.window="showModal = true; orderDetails = $event.detail"
        x-show="showModal"
        class="fixed inset-0 z-50 overflow-y-auto"
        x-cloak
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div 
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity"
                aria-hidden="true"
            >
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div 
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
            >
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Detail Pesanan <span x-text="orderDetails ? '#' + orderDetails.id : ''"></span>
                            </h3>
                            
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-semibold">Informasi Pelanggan</h4>
                                    <p x-text="orderDetails ? orderDetails.user.name : ''"></p>
                                    <p x-text="orderDetails ? orderDetails.user.email : ''"></p>
                                </div>
                                <div>
                                    <h4 class="font-semibold">Detail Pesanan</h4>
                                    <p>Status: <span x-text="orderDetails ? orderDetails.status : ''"></span></p>
                                    <p>Total: Rp <span x-text="orderDetails ? orderDetails.total_amount.toLocaleString('id-ID') : ''"></span></p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h4 class="font-semibold mb-2">Item Pesanan</h4>
                                <table class="w-full border">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border p-2">Produk</th>
                                            <th class="border p-2">Kuantitas</th>
                                            <th class="border p-2">Harga</th>
                                            <th class="border p-2">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-if="orderDetails">
                                            <template x-for="item in orderDetails.order_items">
                                                <tr>
                                                    <td class="border p-2" x-text="item.product_variant.product.name"></td>
                                                    <td class="border p-2" x-text="item.quantity"></td>
                                                    <td class="border p-2" x-text="'Rp ' + item.price.toLocaleString('id-ID')"></td>
                                                    <td class="border p-2" x-text="'Rp ' + (item.quantity * item.price).toLocaleString('id-ID')"></td>
                                                </tr>
                                            </template>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        @click="showModal = false" 
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
