<?php

namespace App\Livewire\Admin\Articles;

use Livewire\Component;
use App\Models\ArticleCategory as Category; // Assuming you have a model for ArticleCategory
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('livewire.layouts.admin-layout')]
class ArticleCategory extends Component
{
    use WithPagination;

    public $categories;
    public $categoryId;
    public $categoryName;

    protected $rules = [
        'categoryName' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::all();
    }

    public function createCategory()
    {
        $this->validate();
        Category::create(['name' => $this->categoryName]);
        $this->reset('categoryName');
        $this->loadCategories();
    }

    public function editCategory($id)
    {
        $category = Category::find($id);
        $this->categoryId = $category->id;
        $this->categoryName = $category->name;
    }

    public function updateCategory()
    {
        $this->validate();
        $category = Category::find($this->categoryId);
        $category->update(['name' => $this->categoryName]);
        $this->reset('categoryName', 'categoryId');
        $this->loadCategories();
    }

    public function deleteCategory($id)
    {
        Category::destroy($id);
        $this->loadCategories();
    }

    public function render()
    {
        return view('livewire.admin.articles.article-category', ['categories' => $this->categories]);
    }
}