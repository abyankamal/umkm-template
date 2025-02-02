<div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Card Statistik -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Pendapatan</p>
                    <p class="text-lg font-semibold">Rp {{ number_format($totalRevenue) }}</p>
                </div>
            </div>
        </div>

        <!-- Tambahkan card statistik lainnya seperti di atas -->
    </div>

    <!-- Filter Tanggal -->
    <div class="mb-6">
        <select wire:model.live="dateRange" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="7">7 Hari Terakhir</option>
            <option value="30">30 Hari Terakhir</option>
            <option value="90">90 Hari Terakhir</option>
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Grafik Penjualan -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Grafik Penjualan</h3>
            <canvas id="salesChart" wire:ignore></canvas>
        </div>

        <!-- Grafik Kategori -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Distribusi Kategori Produk</h3>
            <canvas id="categoryChart" wire:ignore></canvas>
        </div>
    </div>

    <!-- Tabel Produk Terlaris -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Produk Terlaris</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Produk</th>
                            <th class="px-6 py-3">Kategori</th>
                            <th class="px-6 py-3">Terjual</th>
                            <th class="px-6 py-3">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $product)
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4">{{ $product->name }}</td>
                            <td class="px-6 py-4">{{ $product->category->name }}</td>
                            <td class="px-6 py-4">{{ $product->total_sold }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($product->total_revenue) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let salesChart;
        let categoryChart;

        // Inisialisasi Chart
        document.addEventListener('livewire:initialized', function() {
            initCharts(@json($salesChart), @json($categoryChart));
        });

        // Update Chart ketika data berubah
        document.addEventListener('chartUpdated', function(event) {
            updateCharts(event.detail.salesData, event.detail.categoryData);
        });

        function initCharts(salesData, categoryData) {
            // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: salesData.labels,
                    datasets: [{
                        label: 'Penjualan',
                        data: salesData.data,
                        borderColor: 'rgb(59, 130, 246)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            categoryChart = new Chart(categoryCtx, {
                type: 'pie',
                data: {
                    labels: categoryData.labels,
                    datasets: [{
                        data: categoryData.data,
                        backgroundColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)',
                            'rgb(139, 92, 246)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }

        function updateCharts(salesData, categoryData) {
            salesChart.data.labels = salesData.labels;
            salesChart.data.datasets[0].data = salesData.data;
            salesChart.update();

            categoryChart.data.labels = categoryData.labels;
            categoryChart.data.datasets[0].data = categoryData.data;
            categoryChart.update();
        }
    </script>
    @endpush
</div>