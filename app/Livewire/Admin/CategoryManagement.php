<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('livewire.layouts.admin-layout')]
class CategoryManagement extends Component
{
    use WithPagination;

    public $name;
    public $description;
    public $category_id;
    public $isEditing = false;
    public $showDeleteModal = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable',
    ];

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.category-management', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->reset(['name', 'description']);
        session()->flash('message', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $this->category_id = $id;
        $category = Category::findOrFail($id);
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function update()
    {
        $this->validate();

        $category = Category::findOrFail($this->category_id);
        $category->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->reset(['name', 'description', 'isEditing', 'category_id']);
        session()->flash('message', 'Kategori berhasil diperbarui.');
    }

    public function confirmDelete($id)
    {
        $this->category_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $category = Category::findOrFail($this->category_id);
        $category->delete();

        $this->reset(['showDeleteModal', 'category_id']);
        session()->flash('message', 'Kategori berhasil dihapus.');
    }

    public function cancel()
    {
        $this->reset(['name', 'description', 'isEditing', 'category_id', 'showDeleteModal']);
    }
}
