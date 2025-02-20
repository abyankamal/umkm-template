<div>
    <div class="p-4 bg-white rounded-lg shadow-xs">
        <div class="mb-4">
            <h2 class="text-2xl font-semibold">Manage Products</h2>
            <script>document.title = 'Manage Products - UMKM Template';</script>
        </div>

        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Form -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <form wire:submit.prevent="{{ $isEditing ? 'update' : 'create' }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input wire:model="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea wire:model="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <input wire:model="price" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Stock</label>
                    <input wire:model="stock" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Image</label>
                    <input wire:model="image" type="file" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-2">
                    @if($isEditing)
                        <button type="button" wire:click="cancel" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                    @endif
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">{{ $isEditing ? 'Update Product' : 'Add Product' }}</button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Price</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @foreach($products as $product)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3">{{ $product->name }}</td>
                            <td class="px-4 py-3">{{ $product->description }}</td>
                            <td class="px-4 py-3">{{ $product->price }}</td>
                            <td class="px-4 py-3">{{ $product->stock }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="edit({{ $product->id }})" class="px-3 py-1 text-sm font-medium text-blue-600 hover:text-blue-700">Edit</button>
                                    <button wire:click="confirmDelete({{ $product->id }})" class="px-3 py-1 text-sm font-medium text-red-600 hover:text-red-700">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Delete Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Confirm Delete</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Are you sure you want to delete this product?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="delete" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Delete</button>
                        <button wire:click="cancel" type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <!-- Tree Menu Sidebar -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Kategori Produk</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button wire:click="openCategoryModal" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Tambah Kategori
                            </button>
                        </div>
                        <div class="category-tree">
                            @foreach($categories as $category)
                                <div class="category-item">
                                    <div class="d-flex justify-content-between align-items-center py-2">
                                        <span wire:click="selectCategory({{ $category->id }})" class="cursor-pointer {{ $selectedCategory && $selectedCategory->id === $category->id ? 'text-primary font-weight-bold' : '' }}">
                                            <i class="fas fa-folder"></i> {{ $category->name }}
                                        </span>
                                        <div class="btn-group">
                                            <button wire:click="editCategory({{ $category->id }})" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="deleteCategory({{ $category->id }})" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($selectedCategory)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Varian Produk</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button wire:click="openVariantModal" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Tambah Varian
                            </button>
                        </div>
                        <div class="variant-list">
                            @foreach($variants as $variant)
                                <div class="variant-item">
                                    <div class="d-flex justify-content-between align-items-center py-2">
                                        <span>
                                            <i class="fas fa-tag"></i> {{ $variant->name }}
                                        </span>
                                        <div class="btn-group">
                                            <button wire:click="editVariant({{ $variant->id }})" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="deleteVariant({{ $variant->id }})" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Main Content Area -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Daftar Produk {{ $selectedCategory ? 'dalam ' . $selectedCategory->name : '' }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Product list will go here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Modal -->
        <div class="modal fade" id="categoryModal" tabindex="-1" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editingCategory ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveCategory">
                            <div class="form-group">
                                <label>Nama Kategori</label>
                                <input type="text" class="form-control" wire:model="categoryForm.name">
                                @error('categoryForm.name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea class="form-control" wire:model="categoryForm.description"></textarea>
                                @error('categoryForm.description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variant Modal -->
        <div class="modal fade" id="variantModal" tabindex="-1" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editingVariant ? 'Edit Varian' : 'Tambah Varian Baru' }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveVariant">
                            <div class="form-group">
                                <label>Nama Varian</label>
                                <input type="text" class="form-control" wire:model="variantForm.name">
                                @error('variantForm.name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="number" class="form-control" wire:model="variantForm.price">
                                @error('variantForm.price') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="number" class="form-control" wire:model="variantForm.stock">
                                @error('variantForm.stock') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
