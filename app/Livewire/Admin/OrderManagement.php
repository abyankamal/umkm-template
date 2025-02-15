<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts.admin-layout')]
class OrderManagement extends Component
{
    use WithPagination;

    // Filter and Search
    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // UI State
    public $showDetailsModal = false;
    public $selectedOrder = null;
    public $statuses = []; // Added missing property

    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'search' => ['except' => ''],
        'dateFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->statuses = ['pending', 'processing', 'completed', 'cancelled'];
    }

    public function render()
    {
        $query = Order::with(['user', 'orderItems.productVariant'])
            ->when($this->search, function ($query) {
                return $query->where(function($q) {
                    $q->whereHas('user', function ($subQ) {
                        $subQ->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('id', 'like', '%' . $this->search . '%')
                    ->orWhere('total_amount', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFilter, function ($query) {
                return match($this->dateFilter) {
                    'today' => $query->whereDate('created_at', today()),
                    'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                    'month' => $query->whereMonth('created_at', now()->month),
                    default => $query
                };
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.admin.order-management', [
            'orders' => $query->paginate(10),
            'statuses' => $this->statuses
        ]);
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $oldStatus = $order->status;
        
        $order->update(['status' => $status]);
        
        session()->flash('message', "Order #$orderId status updated from $oldStatus to $status");
    }

    public function viewOrderDetails($orderId)
    {
        $this->selectedOrder = Order::with(['user', 'orderItems.productVariant', 'payment', 'shipment'])
            ->findOrFail($orderId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedOrder = null;
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

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'dateFilter']);
    }
}
