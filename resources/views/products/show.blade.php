<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Produk: {{ $product->name }}
            </h2>
            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Product Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-900">Informasi Produk</h3>
                            
                            <dl class="space-y-3">
                                <div class="flex justify-between py-2 border-b">
                                    <dt class="text-sm font-medium text-gray-500">Kode Produk:</dt>
                                    <dd class="text-sm text-gray-900 font-semibold">{{ $product->code }}</dd>
                                </div>
                                
                                <div class="flex justify-between py-2 border-b">
                                    <dt class="text-sm font-medium text-gray-500">Barcode:</dt>
                                    <dd class="text-sm text-gray-900">{{ $product->barcode ?? '-' }}</dd>
                                </div>
                                
                                <div class="flex justify-between py-2 border-b">
                                    <dt class="text-sm font-medium text-gray-500">Nama Produk:</dt>
                                    <dd class="text-sm text-gray-900">{{ $product->name }}</dd>
                                </div>
                                
                                <div class="flex justify-between py-2 border-b">
                                    <dt class="text-sm font-medium text-gray-500">Kategori:</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $product->category->name }}
                                        </span>
                                    </dd>
                                </div>
                                
                                <div class="flex justify-between py-2 border-b">
                                    <dt class="text-sm font-medium text-gray-500">Harga:</dt>
                                    <dd class="text-sm text-gray-900 font-bold text-green-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </dd>
                                </div>
                                
                                <div class="flex justify-between py-2 border-b">
                                    <dt class="text-sm font-medium text-gray-500">Satuan:</dt>
                                    <dd class="text-sm text-gray-900">{{ $product->unit }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-900">Informasi Stok</h3>
                            
                            <!-- Stock Status Card -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-medium text-gray-500">Stok Saat Ini:</span>
                                    <span class="text-2xl font-bold {{ $product->stock <= $product->min_stock ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $product->stock }} {{ $product->unit }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-medium text-gray-500">Stok Minimum:</span>
                                    <span class="text-lg font-semibold text-gray-900">
                                        {{ $product->min_stock }} {{ $product->unit }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-500">Status:</span>
                                    @if($product->stock == 0)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Habis
                                        </span>
                                    @elseif($product->stock <= $product->min_stock)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Stok Menipis
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Tersedia
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Stock Alert -->
                            @if($product->stock <= $product->min_stock)
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            Stok produk ini {{ $product->stock == 0 ? 'habis' : 'menipis' }}. Segera lakukan restok!
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    @if($product->description)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2 text-gray-900">Deskripsi</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $product->description }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Product Image -->
                    @if($product->image)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2 text-gray-900">Gambar Produk</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-w-md rounded-lg shadow-md">
                        </div>
                    </div>
                    @endif

                    <!-- Recent Transactions -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Riwayat Transaksi Terakhir</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Invoice
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipe
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($product->transactions()->latest()->take(5)->get() as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $transaction->invoice_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaction->transaction_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($transaction->type == 'in')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Masuk
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Keluar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaction->quantity }} {{ $product->unit }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaction->user->name }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada transaksi untuk produk ini
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-between">
                        <div>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('products.edit', $product) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                                ✏️ Edit Produk
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                                    onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                    🗑️ Hapus Produk
                                </button>
                            </form>
                            @endif
                        </div>
                        
                        <div>
                            <a href="{{ route('transactions.stock-in') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                                📥 Input Barang Masuk
                            </a>
                            @if($product->stock > 0)
                            <a href="{{ route('transactions.stock-out') }}" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                                📤 Input Barang Keluar
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>