<div class="rounded overflow-hidden shadow-lg bg-white">
    <script>
        document.title = 'Produk Voucher';
    </script>
    <div class="container px-6 mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700">
                Produk Voucher
            </h2>
            <button wire:click="$set('showModal', true)" class="bg-kutamis-purple text-white px-4 py-2 rounded-lg hover:bg-kutamis-purple-hover">
                Tambah Produk ke Voucher
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <input wire:model.live="search" type="text" placeholder="Cari produk..."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-kutamis-purple">
            </div>
            <div>
                <select wire:model.live="voucherFilter"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-kutamis-purple">
                    <option value="">Semua Voucher</option>
                    @foreach($vouchers as $voucher)
                    <option value="{{ $voucher->id }}">{{ $voucher->code }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Products Table -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">No</th>
                            <th wire:click="sortBy('name')" class="px-4 py-3 cursor-pointer">Nama Produk</th>
                            <th wire:click="sortBy('price')" class="px-4 py-3 cursor-pointer">Harga</th>
                            <th wire:click="sortBy('stock')" class="px-4 py-3 cursor-pointer">Stok</th>
                            <th class="px-4 py-3">Voucher Terkait</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @if($products->isEmpty())
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                Tidak ada produk yang ditemukan
                            </td>
                        </tr>
                        @else
                        @foreach($products as $index => $product)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 text-sm">
                                {{ $products->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $product->name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $product->stock }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @foreach($product->vouchers as $voucher)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                    {{ $voucher->code }}
                                    <button wire:click="removeProduct({{ $voucher->id }}, {{ $product->id }})" class="ml-1 text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <button wire:click="$set('showModal', true)" class="text-sm text-blue-500 hover:text-blue-700">
                                    Tambah ke Voucher
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div x-data="{ show: @entangle('showModal') }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div x-show="show" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="assignProducts">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Voucher</label>
                            <select wire:model="voucher_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-kutamis-purple @error('voucher_id') border-red-500 @enderror">
                                <option value="">Pilih Voucher</option>
                                @foreach($vouchers as $voucher)
                                <option value="{{ $voucher->id }}">{{ $voucher->code }}</option>
                                @endforeach
                            </select>
                            @error('voucher_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Produk</label>
                            <select wire:model="selected_products" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-kutamis-purple @error('selected_products') border-red-500 @enderror" multiple>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            @error('selected_products')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-kutamis-purple text-base font-medium text-white hover:bg-kutamis-purple-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kutamis-purple sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" wire:click="resetForm" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    <div x-data="{ show: false, message: '' }"
        x-on:products-assigned.window="show = true; message = $event.detail; setTimeout(() => { show = false }, 2500)"
        x-on:product-removed.window="show = true; message = $event.detail; setTimeout(() => { show = false }, 2500)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg"
        style="display: none;">
        <p x-text="message"></p>
    </div>
</div>