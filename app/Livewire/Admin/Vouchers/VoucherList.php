<?php

namespace App\Livewire\Admin\Vouchers;

use App\Models\Voucher;
use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

#[Layout('livewire.layouts.admin-layout')]
class VoucherList extends Component
{
    use WithPagination;

    // Form Properties
    #[Rule('required|string|max:255')]
    public $code = '';

    #[Rule('required|in:percentage,fixed')]
    public $type = 'fixed';

    #[Rule('required|numeric|min:0')]
    public $value = 0;

    #[Rule('required|numeric|min:0')]
    public $min_purchase = 0;

    #[Rule('required|integer|min:0')]
    public $max_uses = 0;

    #[Rule('required|date')]
    public $start_date = '';

    #[Rule('required|date|after:start_date')]
    public $end_date = '';

    #[Rule('boolean')]
    public $is_active = true;

    #[Rule('array')]
    public $selected_products = [];

    #[Rule('array')]
    public $selected_categories = [];

    // Filter Properties
    public $search = '';
    public $statusFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showModal = false;
    public $editMode = false;
    public $voucherId;

    public function mount()
    {
        $this->resetForm();
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->addDays(30)->format('Y-m-d');
    }

    public function resetForm()
    {
        $this->reset([
            'code',
            'type',
            'value',
            'min_purchase',
            'max_uses',
            'start_date',
            'end_date',
            'is_active',
            'selected_products',
            'selected_categories',
            'voucherId',
            'editMode'
        ]);
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

        $voucher = Voucher::create([
            'code' => $validated['code'],
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_purchase' => $validated['min_purchase'],
            'max_uses' => $validated['max_uses'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $validated['is_active'],
        ]);

        if (!empty($this->selected_products)) {
            $voucher->products()->sync($this->selected_products);
        }

        if (!empty($this->selected_categories)) {
            $voucher->categories()->sync($this->selected_categories);
        }

        $this->resetForm();
        $this->dispatch('voucher-saved', 'Voucher created successfully!');
    }

    public function edit($id)
    {
        $this->editMode = true;
        $this->voucherId = $id;
        $this->showModal = true;

        $voucher = Voucher::with(['products', 'categories'])->findOrFail($id);
        $this->code = $voucher->code;
        $this->type = $voucher->type;
        $this->value = $voucher->value;
        $this->min_purchase = $voucher->min_purchase;
        $this->max_uses = $voucher->max_uses;
        $this->start_date = $voucher->start_date->format('Y-m-d');
        $this->end_date = $voucher->end_date->format('Y-m-d');
        $this->is_active = $voucher->is_active;
        $this->selected_products = $voucher->products->pluck('id')->toArray();
        $this->selected_categories = $voucher->categories->pluck('id')->toArray();
    }

    public function update()
    {
        $validated = $this->validate();

        $voucher = Voucher::findOrFail($this->voucherId);
        $voucher->update([
            'code' => $validated['code'],
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_purchase' => $validated['min_purchase'],
            'max_uses' => $validated['max_uses'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $validated['is_active'],
        ]);

        $voucher->products()->sync($this->selected_products);
        $voucher->categories()->sync($this->selected_categories);

        $this->resetForm();
        $this->dispatch('voucher-saved', 'Voucher updated successfully!');
    }

    public function delete($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->products()->detach();
        $voucher->categories()->detach();
        $voucher->delete();

        $this->dispatch('voucher-deleted', 'Voucher deleted successfully!');
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
        $query = Voucher::query()
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $vouchers = $query->paginate(10);
        $products = Product::all();
        $categories = Category::all();

        return view('livewire.admin.vouchers.voucher-list', [
            'vouchers' => $vouchers,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}