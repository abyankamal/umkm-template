<?php

namespace App\Livewire\Admin\Vouchers;

use App\Models\Voucher;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

#[Layout('livewire.layouts.admin-layout')]
class VoucherProduct extends Component
{
    use WithPagination;

    // Filter Properties
    public $search = '';
    public $voucherFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showModal = false;

    // Form Properties
    #[Rule('required|exists:vouchers,id')]
    public $voucher_id = '';

    #[Rule('required|array')]
    public $selected_products = [];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['voucher_id', 'selected_products']);
        $this->resetValidation();
        $this->showModal = false;
    }

    public function assignProducts()
    {
        $validated = $this->validate();

        $voucher = Voucher::findOrFail($this->voucher_id);
        $voucher->products()->sync($this->selected_products);

        $this->resetForm();
        $this->dispatch('products-assigned', 'Products assigned to voucher successfully!');
    }

    public function removeProduct($voucherId, $productId)
    {
        $voucher = Voucher::findOrFail($voucherId);
        $voucher->products()->detach($productId);

        $this->dispatch('product-removed', 'Product removed from voucher successfully!');
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
            ->when($this->voucherFilter, function ($query) {
                $query->whereHas('vouchers', function ($q) {
                    $q->where('vouchers.id', $this->voucherFilter);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $products = $query->paginate(10);
        $vouchers = Voucher::all();

        return view('livewire.admin.vouchers.voucher-product', [
            'products' => $products,
            'vouchers' => $vouchers,
        ]);
    }
}