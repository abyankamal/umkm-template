<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManagement extends Component
{
    use WithPagination;

    public $selectedStatus = null;
    public $searchTerm = '';

    protected $queryString = [
        'selectedStatus' => ['except' => ''],
        'searchTerm' => ['except' => ''],
    ];

    public function render()
    {
        $query = Order::with(['user', 'orderItems.productVariant'])
            ->when($this->selectedStatus, function ($query) {
                return $query->where('status', $this->selectedStatus);
            })
            ->when($this->searchTerm, function ($query) {
                return $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                })->orWhere('id', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.order-management', [
            'orders' => $query,
            'statuses' => ['pending', 'processing', 'completed', 'cancelled']
        ]);
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $status]);
        session()->flash('message', "Order #$orderId status updated to $status.");
    }

    public function viewOrderDetails($orderId)
    {
        $order = Order::with(['user', 'orderItems.productVariant', 'payment'])
            ->findOrFail($orderId);
        
        $this->dispatch('show-order-details', $order);
    }
}
