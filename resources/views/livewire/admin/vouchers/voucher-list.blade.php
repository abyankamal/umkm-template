<div class="rounded overflow-hidden shadow-lg bg-white">
    <script>
        document.title = 'Manajemen Voucher';
    </script>
    <div class="container px-6 mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700">
                Manajemen Voucher
            </h2>
            <button wire:click="create" class="bg-kutamis-purple text-white px-4 py-2 rounded-lg hover:bg-kutamis-purple-hover">
                Tambah Voucher Baru
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <input wire:model.live="search" type="text" placeholder="Search vouchers..."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-kutamis-purple">
            </div>
            <div>
                <select wire:model.live="statusFilter"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-kutamis-purple">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <!-- Vouchers Table -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">No</th>
                            <th wire:click="sortBy('code')" class="px-4 py-3 cursor-pointer">Kode Voucher</th>
                            <th wire:click="sortBy('type')" class="px-4 py-3 cursor-pointer">Tipe</th>
                            <th wire:click="sortBy('value')" class="px-4 py-3 cursor-pointer">Nilai</th>
                            <th wire:click="sortBy('min_purchase')" class="px-4 py-3 cursor-pointer">Min. Pembelian</th>
                            <th wire:click="sortBy('max_uses')" class="px-4 py-3 cursor-pointer">Max Penggunaan</th>
                            <th wire:click="sortBy('start_date')" class="px-4 py-3 cursor-pointer">Tanggal Mulai</th>
                            <th wire:click="sortBy('end_date')" class="px-4 py-3 cursor-pointer">Tanggal Berakhir</th>
                            <th wire:click="sortBy('is_active')" class="px-4 py-3 cursor-pointer">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @if($vouchers->isEmpty())
                        <tr>
                            <td colspan="10" class="text-center px-4 py-3 text-gray-500">
                                Belum Ada Voucher
                            </td>
                        </tr>
                        @else
                        @foreach($vouchers as $index => $voucher)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">{{ $voucher->code }}</td>
                            <td class="px-4 py-3">{{ ucfirst($voucher->type) }}</td>
                            <td class="px-4 py-3">
                                @if($voucher->type === 'percentage')
                                {{ $voucher->value }}%
                                @else
                                Rp {{ number_format($voucher->value, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="px-4 py-3">Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $voucher->max_uses }}</td>
                            <td class="px-4 py-3">{{ $voucher->start_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $voucher->end_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-sm rounded-full {{ $voucher->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $voucher->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="edit({{ $voucher->id }})"
                                    class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full mr-2">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $voucher->id }})"
                                    onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                    class="text-sm bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="fixed inset-0 z-30 flex items-center justify-center overflow-auto bg-black bg-opacity-50" x-show="$wire.showModal"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="relative px-6 py-4 bg-white rounded-lg shadow-lg max-w-md w-full mx-4" @click.away="$wire.showModal = false">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                {{ $editMode ? 'Edit Voucher' : 'Add New Voucher' }}
            </h3>
            <form wire:submit="{{ $editMode ? 'update' : 'store' }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kode Voucher</label>
                        <input type="text" wire:model="code"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe</label>
                        <select wire:model="type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage</option>
                        </select>
                        @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nilai</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" wire:model="value" step="0.01"
                                class="block w-full rounded-md border-gray-300 pl-7 pr-12 focus:border-blue-500 focus:ring-blue-500">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-500 sm:text-sm">{{ $type === 'percentage' ? '%' : 'Rp' }}</span>
                            </div>
                        </div>
                        @error('value') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Minimum Pembelian</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" wire:model="min_purchase" step="0.01"
                                class="block w-full rounded-md border-gray-300 pl-12 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        @error('min_purchase') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Maksimum Penggunaan</label>
                        <input type="number" wire:model="max_uses"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('max_uses') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" wire:model="start_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Berakhir</label>
                        <input type="date" wire:model="end_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Active</span>
                            </label>
                        </div>
                        @error('is_active') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Produk Terkait</label>
                        <select wire:model="selected_products" multiple
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('selected_products') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kategori Terkait</label>
                        <select wire:model="selected_categories" multiple
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('selected_categories') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-5 flex justify-end space-x-3">
                    <button type="button" wire:click="resetForm"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700">
                        {{ $editMode ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications -->
    <div x-data="{ show: false, message: '' }"
        x-on:voucher-saved.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
        x-on:voucher-deleted.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
        class="fixed bottom-4 right-4">
        <div x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <span x-text="message"></span>
        </div>
    </div>
</div>