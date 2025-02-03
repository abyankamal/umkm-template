<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layout.admin')]
class Dashboard extends Component
{
    public $totalRevenue;
    public $totalOrders;
    public $totalProducts;
    public $totalCustomers;
    public $salesChart;
    public $categoryChart;
    public $topProducts;
    public $dateRange = '30';

    public function mount()
    {
        $this->loadDummyData();
    }

    public function updatedDateRange()
    {
        $this->loadDummyData();
        $this->dispatch('chartUpdated', [
            'salesData' => $this->salesChart,
            'categoryData' => $this->categoryChart
        ]);
    }

    private function loadDummyData()
    {
        // Statistik dasar dummy
        $this->totalRevenue = 15750000;
        $this->totalOrders = 234;
        $this->totalProducts = 50;
        $this->totalCustomers = 180;

        // Data dummy untuk grafik penjualan
        $labels = collect(range($this->dateRange, 1))->map(function ($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        });

        $this->salesChart = [
            'labels' => $labels,
            'data' => $labels->map(function () {
                return rand(100000, 1000000);
            })
        ];

        // Data dummy untuk grafik kategori
        $this->categoryChart = [
            'labels' => ['Elektronik', 'Fashion', 'Makanan', 'Kesehatan', 'Olahraga'],
            'data' => [25, 20, 15, 18, 12]
        ];

        // Data dummy untuk produk terlaris
        $this->topProducts = collect([
            [
                'name' => 'Smartphone XYZ',
                'category' => 'Elektronik',
                'total_sold' => 45,
                'total_revenue' => 13500000
            ],
            [
                'name' => 'Sepatu Running ABC',
                'category' => 'Olahraga',
                'total_sold' => 38,
                'total_revenue' => 7600000
            ],
            [
                'name' => 'Kemeja Premium',
                'category' => 'Fashion',
                'total_sold' => 32,
                'total_revenue' => 4800000
            ],
            [
                'name' => 'Vitamin C Plus',
                'category' => 'Kesehatan',
                'total_sold' => 28,
                'total_revenue' => 2800000
            ],
            [
                'name' => 'Snack Box Special',
                'category' => 'Makanan',
                'total_sold' => 25,
                'total_revenue' => 1250000
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
