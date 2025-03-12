<div class="rounded overflow-hidden shadow-lg bg-white">
    <script>document.title = 'Manajemen Kategori Produk';</script>
        {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
        <div class="container px-6 mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h2 class="my-6 text-2xl font-semibold text-gray-700">
                    Manajemen Kategori Produk
                </h2>
                <button wire:click="create" class="bg-kutamis-purple text-white px-4 py-2 rounded-lg hover:bg-kutamis-purple-hover">
                    Tambah Kategori Baru
                </button>
            </div>
    
            <!-- Filters -->
            <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input wire:model.live="search" type="text" placeholder="Search products..."
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-kutamis-purple">
                </div>
                <div>
                    <select wire:model.live="categoryFilter"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-kutamis-purple">
                        <option value="" class="bg-kutamis-purple">All Categories</option>
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
                                <th class="px-4 py-3 cursor-pointer">No</th>
                                <th wire:click="sortBy('name')" class="px-4 py-3 cursor-pointer">Nama Kategori</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @if($categories->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center px-4 py-3 text-gray-500">
                                        Belum Ada Kategori
                                    </td>
                                </tr>
                            @else
                                @foreach($categories as $category)
                                    <tr class="text-gray-700">
                                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">{{ $category->name }}</td>
                                        <td class="px-4 py-3">
                                            <button wire:click="edit({{ $category->id }})"
                                                class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full mr-2">
                                                Edit
                                            </button>
                                            <button wire:click="delete({{ $category->id }})"
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
                    {{ $categories->links() }}
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
                    {{ $editMode ? 'Edit Kategori' : 'Tambah Kategori' }}
                </h3>
                <form wire:submit="{{ $editMode ? 'update' : 'store' }}">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text" wire:model="name"
                                class="w-full px-2 py-1 border rounded-lg focus:outline-none focus:border-kutamis-purple" placeholder="Masukan Nama Kategori">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
    
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" rows="5"
                                class="w-full px-2 py-1 border rounded-lg focus:outline-none focus:border-kutamis-purple" placeholder="Masukan Deskripsi Produk"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
    
                    <div class="mt-5 flex justify-end space-x-3">
                        <button type="button" wire:click="resetForm"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-kutamis-purple border border-transparent rounded-md shadow-sm hover:bg-kutamis-purple-hover">
                            {{ $editMode ? 'Ubah' : 'Simpan' }}
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
    