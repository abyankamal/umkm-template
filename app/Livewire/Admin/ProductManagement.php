<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('livewire.layouts.admin-layout')]
class ProductManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Form Properties
    public $name;
    public $description;
    public $price;
    public $stock;
    public $image;
    public $product_id;
    
    // UI State
    public $showForm = false;
    public $isEditing = false;
    public $showDeleteModal = false;
    
    // Filter and Sort
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'image' => 'nullable|image|max:2048',
    ];

    public function render()
    {
        $query = Product::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $products = $query->paginate(10);

        return view('livewire.admin.product-management', [
            'products' => $products
        ]);
    }

    public function create()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $imagePath,
        ]);

        session()->flash('message', 'Product created successfully!');
        $this->reset();
        $this->showForm = false;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock = $product->stock;
        
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($this->product_id);

        $imagePath = $product->image;
        if ($this->image) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $this->image->store('products', 'public');
        }

        $product->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $imagePath,
        ]);

        session()->flash('message', 'Product updated successfully!');
        $this->reset();
        $this->showForm = false;
    }

    public function confirmDelete($id)
    {
        $this->product_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $product = Product::findOrFail($this->product_id);
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        session()->flash('message', 'Product deleted successfully!');
        $this->showDeleteModal = false;
    }

    public function resetForm()
    {
        $this->reset();
        $this->resetValidation();
    }
}