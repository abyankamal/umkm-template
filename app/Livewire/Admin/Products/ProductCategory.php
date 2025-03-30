<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Livewire\WithoutUrlPagination;

#[Layout('livewire.layouts.admin-layout')]
class ProductCategory extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Rule('required|string|max:255', message: 'Nama kategori wajib diisi dan maksimal 255 karakter.')]
    public $name = '';

    #[Rule('nullable|string', message: 'Deskripsi harus berupa teks.')]
    public $description = '';

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $editMode = false;
    public $categoryId;

    public $deleteId;

    public $showDeleteModal = false;

    // Properties that should be reset after actions
    protected $resetProperties = ['name', 'description', 'categoryId', 'editMode'];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset($this->resetProperties);
        $this->resetValidation();
        $this->showModal = false;
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function store()
    {
        $validated = $this->validate();

        try {
            Category::create($validated);
            $this->resetForm();
            $this->dispatch('category-saved', 'Kategori berhasil ditambahkan!');
        } catch (\Exception $e) {
            $this->dispatch('category-error', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->editMode = true;
        $this->categoryId = $id;
        $this->showModal = true;

        $category = Category::findOrFail($id);
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function update()
    {
        $validated = $this->validate();

        try {
            $category = Category::findOrFail($this->categoryId);
            $category->update($validated);
            $this->resetForm();
            $this->dispatch('category-saved', 'Kategori berhasil diperbarui!');
        } catch (\Exception $e) {
            $this->dispatch('category-error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            Category::findOrFail($id)->delete();
            $this->dispatch('category-deleted', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('category-error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->showDeleteModal = false;
    }

    public function deleteConfirmed()
    {
        $this->delete($this->deleteId);
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    public function render()
    {
        $query = Category::query();

        // Only apply the search filter if search is not empty
        if (!empty($this->search)) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        return view('livewire.admin.products.product-category', [
            'categories' => $query->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10)
        ]);
    }
}
