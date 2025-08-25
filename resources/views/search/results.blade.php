<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hasil Pencarian: "{{ $query }}"
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <p class="text-gray-600">
                        Ditemukan <span class="font-bold text-purple-600">{{ $totalResults }}</span> hasil untuk
                        <span class="font-bold">"{{ $query }}"</span>
                    </p>
                </div>
            </div>

            <!-- Products Results -->
            @if ($results['products']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-4">
                        <h3 class="text-lg font-semibold text-white">📦 Produk ({{ $results['products']->count() }})
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($results['products'] as $product)
                                <a href="{{ route('products.show', $product) }}"
                                    class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $product->name }}</h4>
                                            <p class="text-sm text-gray-500">Kode: {{ $product->code }}</p>
                                            <p class="text-sm text-gray-500">Kategori: {{ $product->category->name }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500">Stok: {{ $product->stock }}
                                                {{ $product->unit }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Transactions Results -->
            @if ($results['transactions']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="bg-gradient-to-r from-green-600 to-teal-600 p-4">
                        <h3 class="text-lg font-semibold text-white">📋 Transaksi
                            ({{ $results['transactions']->count() }})</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Invoice</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Produk</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Jumlah</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($results['transactions'] as $transaction)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm">{{ $transaction->invoice_number }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $transaction->product->name }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                @if ($transaction->type == 'in')
                                                    <span class="text-green-600">Masuk</span>
                                                @else
                                                    <span class="text-red-600">Keluar</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm">{{ $transaction->quantity }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $transaction->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Suppliers Results -->
            @if ($results['suppliers']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 p-4">
                        <h3 class="text-lg font-semibold text-white">🏢 Supplier ({{ $results['suppliers']->count() }})
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($results['suppliers'] as $supplier)
                                <a href="{{ route('suppliers.edit', $supplier) }}"
                                    class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <h4 class="font-semibold text-gray-900">{{ $supplier->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $supplier->contact_person }}</p>
                                    <p class="text-sm text-gray-500">{{ $supplier->phone }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Customers Results -->
            @if ($results['customers']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="bg-gradient-to-r from-pink-600 to-rose-600 p-4">
                        <h3 class="text-lg font-semibold text-white">👥 Customer ({{ $results['customers']->count() }})
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($results['customers'] as $customer)
                                <a href="{{ route('customers.edit', $customer) }}"
                                    class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <h4 class="font-semibold text-gray-900">{{ $customer->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $customer->contact_person }}</p>
                                    <p class="text-sm text-gray-500">{{ $customer->phone }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- No Results -->
            @if ($totalResults == 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada hasil</h3>
                        <p class="mt-1 text-sm text-gray-500">Coba kata kunci yang berbeda.</p>
                        <div class="mt-6">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
