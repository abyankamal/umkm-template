<?php

namespace App\Livewire\Admin\Articles;

use Livewire\Component;
use App\Models\ArticleCategory as Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;
use Illuminate\Support\Str;

#[Layout('livewire.layouts.admin-layout')]
class ArticleCategory extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $categoryId;
    public $categoryToDelete;

    #[Rule('required|string|max:255')]
    public $name;

    #[Rule('nullable|string')]
    public $description;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255' . ($this->categoryId ? ',name,' . $this->categoryId . ',id' : ''),
            'description' => 'nullable|string',
        ];
    }

    public function mount()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();
        Category::create([
            'name' => $this->name,
            'description' => $this->description,
            'slug' => Str::slug($this->name),
        ]);
        $this->resetForm();
        $this->dispatch('category-saved', 'Kategori berhasil ditambahkan');
    }

    public function update()
    {
        $this->validate();
        $category = Category::find($this->categoryId);
        $category->update([
            'name' => $this->name,
            'description' => $this->description,
            'slug' => Str::slug($this->name),
        ]);
        $this->resetForm();
        $this->dispatch('category-saved', 'Kategori berhasil diperbarui');
    }

    public function confirmDelete($id)
    {
        $this->categoryToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteConfirmed()
    {
        Category::destroy($this->categoryToDelete);
        $this->showDeleteModal = false;
        $this->dispatch('category-deleted', 'Kategori berhasil dihapus');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->categoryToDelete = null;
    }

    public function resetForm()
    {
        $this->reset(['categoryId', 'name', 'description', 'showModal', 'editMode']);
        $this->resetValidation();
    }

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.articles.article-category', [
            'categories' => $categories
        ]);
    }
}
