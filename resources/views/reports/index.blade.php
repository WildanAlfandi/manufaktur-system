<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Laporan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Date Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('reports.index') }}" class="flex gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="rounded-md border-gray-300 shadow-sm">
                        </div>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Filter
                        </button>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Total Produk</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totals['total_products'] }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Nilai Total Stok</div>
                        <div class="text-2xl font-bold text-green-600">Rp
                            {{ number_format($totals['total_stock_value'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Barang Masuk</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $totals['total_in'] }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Barang Keluar</div>
                        <div class="text-2xl font-bold text-red-600">{{ $totals['total_out'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex">
                        <button onclick="showTab('stock')" id="stock-tab"
                            class="tab-btn border-b-2 border-blue-500 py-2 px-4 text-sm font-medium text-blue-600">
                            Laporan Stok
                        </button>
                        <button onclick="showTab('transaction')" id="transaction-tab"
                            class="tab-btn border-b-2 border-transparent py-2 px-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Ringkasan Transaksi
                        </button>
                        <button onclick="showTab('top-products')" id="top-products-tab"
                            class="tab-btn border-b-2 border-transparent py-2 px-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Produk Terlaris
                        </button>
                    </nav>
                </div>

                <!-- Tab Contents -->
                <div class="p-6">
                    <!-- Stock Report Tab -->
                    <div id="stock-content" class="tab-content">
                        <div class="flex justify-between mb-4">
                            <h3 class="text-lg font-semibold">Laporan Stok Saat Ini</h3>
                            <a href="{{ route('reports.export.stock') }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                📥 Export CSV
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Kategori</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Harga</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Nilai Stok</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($stockReport as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $product->code }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $product->category->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $product->stock }}
                                                {{ $product->unit }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">Rp
                                                {{ number_format($product->stock_value, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($product->stock == 0)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Habis</span>
                                                @elseif($product->stock <= $product->min_stock)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menipis</span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Transaction Summary Tab -->
                    <div id="transaction-content" class="tab-content hidden">
                        <div class="flex justify-between mb-4">
                            <h3 class="text-lg font-semibold">Ringkasan Transaksi</h3>
                            <a href="{{ route('reports.export.transactions') }}?start_date={{ $startDate }}&end_date={{ $endDate }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                📥 Export CSV
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Barang Masuk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Nilai Masuk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Barang Keluar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Nilai Keluar</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($transactionSummary as $summary)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ Carbon\Carbon::parse($summary->date)->format('d/m/Y') }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">
                                                {{ $summary->total_in }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">Rp
                                                {{ number_format($summary->value_in, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">
                                                {{ $summary->total_out }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">Rp
                                                {{ number_format($summary->value_out, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Top Products Tab -->
                    <div id="top-products-content" class="tab-content hidden">
                        <h3 class="text-lg font-semibold mb-4">Top 10 Produk Terlaris</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Rank</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Total Terjual</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Total Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($topProducts as $index => $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                                #{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div>{{ $item->product->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $item->product->code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                                {{ $item->total_quantity }} {{ $item->product->unit }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">Rp
                                                {{ number_format($item->total_value, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active state from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected content
            document.getElementById(tabName + '-content').classList.remove('hidden');

            // Activate selected tab
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
        }
    </script>
</x-app-layout>
