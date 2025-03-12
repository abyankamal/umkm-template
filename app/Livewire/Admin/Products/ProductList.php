<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

#[Layout('livewire.layouts.admin-layout')]
class ProductList extends Component
{
    use WithPagination;

    // Form Properties
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|numeric')]
    public $price = '';

    #[Rule('required|integer|min:0')]
    public $stock = 0;

    #[Rule('required|exists:categories,id')]
    public $category_id = '';

    #[Rule('nullable|string')]
    public $description = '';

    // Filter Properties
    public $search = '';
    public $categoryFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $editMode = false;
    public $productId;

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'price', 'stock', 'category_id', 'description', 'productId', 'editMode']);
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
        
        Product::create($validated);
        
        $this->resetForm();
        $this->dispatch('product-saved', 'Product created successfully!');
    }

    public function edit($id)
    {
        $this->editMode = true;
        $this->productId = $id;
        $this->showModal = true;

        $product = Product::findOrFail($id);
        $this->name = $product->name;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->category_id = $product->category_id;
        $this->description = $product->description;
    }

    public function update()
    {
        $validated = $this->validate();
        
        $product = Product::findOrFail($this->productId);
        $product->update($validated);
        
        $this->resetForm();
        $this->dispatch('product-saved', 'Product updated successfully!');
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        $this->dispatch('product-deleted', 'Product deleted successfully!');
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
        $query = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $products = $query->paginate(10);
        $categories = Category::all();

        return view('livewire.admin.products.product-list', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
