<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

#[Layout('livewire.layouts.admin-layout')]
class ProductCategory extends Component
{
    use WithPagination;

    #[Rule('required|string|max:255', 'The name field is required and must be a string with a maximum length of 255.')] 
    public $name = '';

    #[Rule('nullable|string', 'The description must be a string.')] 
    public $description = '';

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $editMode = false;
    public $categoryId;

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'categoryId', 'editMode']);
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
        Category::create($validated);
        $this->resetForm();
        $this->dispatch('category-saved', 'Category created successfully!');
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
        $category = Category::findOrFail($this->categoryId);
        $category->update($validated);
        $this->resetForm();
        $this->dispatch('category-saved', 'Category updated successfully!');
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->dispatch('category-deleted', 'Category deleted successfully!');
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

    public function render()
    {
        $query = Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $categories = $query->paginate(10);

        return view('livewire.admin.products.product-category', [
            'categories' => $categories,
        ]);
    }
}