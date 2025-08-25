<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📥 Input Barang Masuk
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('transactions.stock-in.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Produk -->
                            <div>
                                <label for="product_id" class="block text-sm font-medium text-gray-700">Produk *</label>
                                <select name="product_id" id="product_id" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    onchange="updateProductInfo(this)">
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                            data-stock="{{ $product->stock }}"
                                            data-unit="{{ $product->unit }}"
                                            data-price="{{ $product->price }}">
                                            {{ $product->code }} - {{ $product->name }} (Stok: {{ $product->stock }} {{ $product->unit }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                                <select name="supplier_id" id="supplier_id"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Pilih Supplier (Opsional)</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah *</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" name="quantity" id="quantity" required min="1" value="{{ old('quantity', 1) }}"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300"
                                        onchange="calculateTotal()">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm" id="unit-label">
                                        unit
                                    </span>
                                </div>
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Harga per Unit -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Harga per Unit *</label>
                                <input type="number" name="price" id="price" required min="0" step="0.01" value="{{ old('price', 0) }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    onchange="calculateTotal()">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label for="transaction_date" class="block text-sm font-medium text-gray-700">Tanggal Transaksi *</label>
                                <input type="date" name="transaction_date" id="transaction_date" required value="{{ old('transaction_date', date('Y-m-d')) }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('transaction_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Total -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total</label>
                                <div class="mt-1 text-2xl font-bold text-green-600" id="total-display">
                                    Rp 0
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Stok Saat Ini: <span id="current-stock" class="font-bold">-</span>
                                    </p>
                                    <p class="text-sm text-blue-700">
                                        Stok Setelah Input: <span id="after-stock" class="font-bold">-</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                💾 Simpan Barang Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateProductInfo(select) {
            const selectedOption = select.options[select.selectedIndex];
            const stock = selectedOption.dataset.stock || 0;
            const unit = selectedOption.dataset.unit || 'unit';
            const price = selectedOption.dataset.price || 0;
            
            document.getElementById('unit-label').textContent = unit;
            document.getElementById('current-stock').textContent = stock + ' ' + unit;
            document.getElementById('price').value = price;
            
            updateAfterStock();
            calculateTotal();
        }

        function updateAfterStock() {
            const currentStock = parseInt(document.getElementById('current-stock').textContent) || 0;
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const unit = document.getElementById('unit-label').textContent;
            
            document.getElementById('after-stock').textContent = (currentStock + quantity) + ' ' + unit;
        }

        function calculateTotal() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const price = parseFloat(document.getElementById('price').value) || 0;
            const total = quantity * price;
            
            document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
            updateAfterStock();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
        });
    </script>
</x-app-layout>