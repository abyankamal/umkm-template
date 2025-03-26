<div class="rounded overflow-hidden shadow-lg bg-white">
    <script>
        document.title = 'Manajemen Relasi Artikel-Produk';
    </script>
    <div class="container px-6 mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="my-6 text-2xl font-semibold text-gray-700">
                Manajemen Relasi Artikel-Produk
            </h2>
            <button wire:click="create" class="bg-kutamis-purple text-white px-4 py-2 rounded-lg hover:bg-kutamis-purple-hover">
                Tambah Relasi Baru
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input wire:model.live="search" type="text" placeholder="Cari produk atau artikel..."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-kutamis-purple">
            </div>
            <div>
                <select wire:model.live="filters.category" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-kutamis-purple">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="perPage" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-kutamis-purple">
                    <option value="10">10 per halaman</option>
                    <option value="25">25 per halaman</option>
                    <option value="50">50 per halaman</option>
                    <option value="100">100 per halaman</option>
                </select>
            </div>
        </div>

        <!-- Relations Table -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">No</th>
                            <th wire:click="sortBy('article_title')" class="px-4 py-3 cursor-pointer">
                                Artikel
                                @if($sortField === 'article_title')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </th>
                            <th wire:click="sortBy('product_name')" class="px-4 py-3 cursor-pointer">
                                Produk
                                @if($sortField === 'product_name')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </th>
                            <th wire:click="sortBy('created_at')" class="px-4 py-3 cursor-pointer">
                                Tanggal Dibuat
                                @if($sortField === 'created_at')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @if($relations->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center px-4 py-3 text-gray-500">
                                Belum Ada Relasi Artikel-Produk
                            </td>
                        </tr>
                        @else
                        @foreach($relations as $index => $relation)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3">{{ $relations->firstItem() + $index }}</td>
                            <td class="px-4 py-3">{{ $relation->article_title }}</td>
                            <td class="px-4 py-3">{{ $relation->product_name }}</td>
                            <td class="px-4 py-3">{{ $relation->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-4 py-3">
                                <button wire:click="edit({{ $relation->id }})"
                                    class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full mr-2">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $relation->id }})"
                                    class="text-sm bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t">
                    {{ $relations->links('vendor.livewire.tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div x-data="{ open: false }" 
         x-show="open" 
         @open-modal.window="if ($event.detail.modal === 'relation-form') open = true" 
         @close-modal.window="if ($event.detail.modal === 'relation-form') open = false"
         class="fixed inset-0 z-30 flex items-center justify-center overflow-auto bg-black bg-opacity-50"
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0">
        <div class="relative px-6 py-4 bg-white rounded-lg shadow-lg max-w-md w-full mx-4" @click.away="open = false">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                {{ $editMode ? 'Edit Relasi Artikel-Produk' : 'Tambah Relasi Artikel-Produk' }}
            </h3>
            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Artikel</label>
                        <select wire:model="article_id"
                            class="w-full px-2 py-1 border rounded-lg focus:outline-none focus:border-kutamis-purple">
                            <option value="">Pilih Artikel</option>
                            @foreach($articles as $article)
                                <option value="{{ $article->id }}">{{ $article->title }}</option>
                            @endforeach
                        </select>
                        @error('article_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Produk</label>
                        <select wire:model="product_id"
                            class="w-full px-2 py-1 border rounded-lg focus:outline-none focus:border-kutamis-purple">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('product_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-5 flex justify-end space-x-3">
                    <button type="button" @click="open = false" wire:click="resetForm"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                        Batal
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
    <div x-data="{ show: false, message: '', type: 'success' }"
        @alert.window="show = true; message = $event.detail.message; type = $event.detail.type || 'success'; setTimeout(() => show = false, 3000)"
        class="fixed bottom-4 right-4 z-50">
        <div x-show="show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            :class="{'bg-green-500': type === 'success', 'bg-red-500': type === 'error'}"
            class="text-white px-6 py-3 rounded-lg shadow-lg">
            <span x-text="message"></span>
        </div>
    </div>
</div>