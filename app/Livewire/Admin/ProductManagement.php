<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManagement extends Component
{
    use WithPagination;

    public $name;
    public $description;
    public $price;
    public $stock;
    public $image;
    public $product_id;
    public $isEditing = false;
    public $showDeleteModal = false;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'image' => 'nullable|image|max:2048',
    ];

    public function render()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.product-management', [
            'products' => $products
        ]);
    }

    public function create()
    {
        $this->validate();

        Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $this->image,
        ]);

        $this->reset(['name', 'description', 'price', 'stock', 'image']);
        session()->flash('message', 'Product successfully added.');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $this->product_id = $id;
        $product = Product::findOrFail($id);
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->image = $product->image;
    }

    public function update()
    {
        $this->validate();

        $product = Product::findOrFail($this->product_id);
        $product->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $this->image,
        ]);

        $this->reset(['name', 'description', 'price', 'stock', 'image', 'isEditing', 'product_id']);
        session()->flash('message', 'Product successfully updated.');
    }

    public function confirmDelete($id)
    {
        $this->product_id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $product = Product::findOrFail($this->product_id);
        $product->delete();

        $this->reset(['showDeleteModal', 'product_id']);
        session()->flash('message', 'Product successfully deleted.');
    }

    public function cancel()
    {
        $this->reset(['name', 'description', 'price', 'stock', 'image', 'isEditing', 'product_id', 'showDeleteModal']);
    }
}