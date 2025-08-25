<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Kategori: {{ $category->name }}
            </h2>
            <a href="{{ route('categories.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transform transition hover:scale-105 duration-200">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Form Card with Animation -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg animate-fade-in">
                <!-- Gradient Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Edit Kategori</h3>
                                <p class="text-sm text-white opacity-90 mt-1">Perbarui informasi kategori produk</p>
                            </div>
                        </div>
                        <!-- Category Stats -->
                        <div class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                            <p class="text-xs text-white opacity-75">Total Produk</p>
                            <p class="text-2xl font-bold text-white">{{ $category->products()->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Current Category Info -->
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Informasi Saat Ini</p>
                            <div class="mt-2 flex items-center space-x-4">
                                <div>
                                    <span class="text-xs text-gray-500">Dibuat pada:</span>
                                    <span
                                        class="text-sm font-medium text-gray-900 ml-1">{{ $category->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Terakhir diupdate:</span>
                                    <span
                                        class="text-sm font-medium text-gray-900 ml-1">{{ $category->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <form method="POST" action="{{ route('categories.update', $category) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Category Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $category->name) }}" required
                                class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 @error('name') border-red-500 @enderror"
                                placeholder="Contoh: Elektronik, Furniture, dll">
                        </div>
                        @error('name')
                            <div class="flex items-center mt-2">
                                <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Deskripsi
                            <span class="text-xs text-gray-400 ml-2">(Opsional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16m-7 6h7" />
                                </svg>
                            </div>
                            <textarea name="description" id="description" rows="4"
                                class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 @error('description') border-red-500 @enderror"
                                placeholder="Jelaskan secara singkat tentang kategori ini...">{{ old('description', $category->description) }}</textarea>
                        </div>
                        @error('description')
                            <div class="flex items-center mt-2">
                                <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Products in Category -->
                    @if ($category->products()->count() > 0)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Perhatian:</strong> Kategori ini memiliki
                                        {{ $category->products()->count() }} produk terkait.
                                        Mengubah nama kategori akan mempengaruhi semua produk tersebut.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Sample Products -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Beberapa Produk dalam Kategori Ini:
                            </h4>
                            <div class="space-y-2">
                                @foreach ($category->products()->take(5)->get() as $product)
                                    <div class="flex items-center justify-between bg-white rounded p-2 shadow-sm">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-purple-100 rounded flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-purple-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $product->code }}</p>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500">Stok: {{ $product->stock }}
                                            {{ $product->unit }}</span>
                                    </div>
                                @endforeach
                                @if ($category->products()->count() > 5)
                                    <p class="text-xs text-gray-500 text-center pt-2">
                                        ... dan {{ $category->products()->count() - 5 }} produk lainnya
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Visual Preview -->
                    <div class="bg-gray-50 rounded-lg p-6 border-2 border-dashed border-gray-300">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Preview Kategori</h4>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h5 class="font-semibold text-gray-900" id="preview-name">{{ $category->name }}
                                    </h5>
                                    <p class="text-sm text-gray-500" id="preview-description">
                                        {{ $category->description ?? 'Tidak ada deskripsi' }}</p>
                                </div>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    {{ $category->products()->count() }} Produk
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div>
                            @if ($category->products()->count() == 0)
                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus Kategori
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <a href="{{ route('categories.index') }}"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transform transition hover:scale-105 duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Update Kategori
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Change History (Optional) -->
            <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700">Riwayat Perubahan</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-900">
                                    Kategori terakhir diupdate
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $category->updated_at->format('d M Y, H:i') }}
                                    ({{ $category->updated_at->diffForHumans() }})
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-900">
                                    Kategori dibuat
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $category->created_at->format('d M Y, H:i') }}
                                    ({{ $category->created_at->diffForHumans() }})
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Live Preview -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const descInput = document.getElementById('description');
            const previewName = document.getElementById('preview-name');
            const previewDesc = document.getElementById('preview-description');

            nameInput.addEventListener('input', function() {
                previewName.textContent = this.value || 'Nama Kategori';
            });

            descInput.addEventListener('input', function() {
                previewDesc.textContent = this.value || 'Tidak ada deskripsi';
            });
        });
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.5s ease-out;
        }
    </style>
</x-app-layout>
