<div class="rounded overflow-hidden shadow-lg bg-white">
<script>document.title = 'Manage Products';</script>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="container px-6 mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700">
                Products Management
            </h2>
            <button wire:click="create" class="bg-kutamis-purple text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Add New Product
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input wire:model.live="search" type="text" placeholder="Search products..."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <select wire:model.live="categoryFilter"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                            <th wire:click="sortBy('name')" class="px-4 py-3 cursor-pointer">Name</th>
                            <th wire:click="sortBy('category_id')" class="px-4 py-3 cursor-pointer">Category</th>
                            <th wire:click="sortBy('price')" class="px-4 py-3 cursor-pointer">Price</th>
                            <th wire:click="sortBy('stock')" class="px-4 py-3 cursor-pointer">Stock</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @foreach($products as $product)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3">{{ $product->name }}</td>
                                <td class="px-4 py-3">{{ $product->category->name }}</td>
                                <td class="px-4 py-3">{{ number_format($product->price, 2) }}</td>
                                <td class="px-4 py-3">{{ $product->stock }}</td>
                                <td class="px-4 py-3">
                                    <button wire:click="edit({{ $product->id }})"
                                        class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full mr-2">
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $product->id }})"
                                        onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                        class="text-sm bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $products->links() }}
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
                {{ $editMode ? 'Edit Product' : 'Add New Product' }}
            </h3>
            <form wire:submit="{{ $editMode ? 'update' : 'store' }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" wire:model="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select wire:model="category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" step="0.01" wire:model="price"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stock</label>
                        <input type="number" wire:model="stock"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea wire:model="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
        x-on:product-saved.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
        x-on:product-deleted.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
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
