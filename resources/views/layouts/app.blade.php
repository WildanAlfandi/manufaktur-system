<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Manufaktur') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Custom Styles -->
    <style>
        @keyframes slideInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-slide-in-up {
            animation: slideInUp 0.5s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- NAVIGATION BAR START -->
        <nav class="bg-white border-b border-gray-200" x-data="{ mobileMenuOpen: false, userMenuOpen: false, notifOpen: false, searchOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- LEFT SIDE: Logo & Desktop Menu -->
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-lg flex items-center justify-center shadow-lg mr-3 transform hover:rotate-3 transition-transform duration-200">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <span
                                    class="hidden sm:block text-xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                                    Sistem Manufaktur
                                </span>
                                <span class="sm:hidden text-lg font-bold text-purple-600">
                                    SM
                                </span>
                            </a>
                        </div>

                        <!-- GLOBAL SEARCH BAR (Desktop) -->
                        <div class="hidden lg:flex flex-1 items-center justify-center px-6 max-w-md">
                            <div class="w-full">
                                <form action="{{ route('search.global') }}" method="GET" class="relative">
                                    <div class="relative">
                                        <input type="search" name="q" value="{{ request('q') }}"
                                            placeholder="Cari produk, transaksi, supplier..."
                                            class="w-full pl-10 pr-10 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-150"
                                            autocomplete="off">

                                        <!-- Search Icon -->
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>

                                        <!-- Keyboard Shortcut Hint -->
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <kbd
                                                class="hidden sm:inline-flex items-center px-2 py-0.5 text-xs font-semibold text-gray-400 bg-gray-100 rounded">
                                                Ctrl+K
                                            </kbd>
                                        </div>
                                    </div>

                                    <!-- Quick Filter Options (Optional) -->
                                    <div class="absolute z-50 w-full mt-1 hidden" id="searchFilters">
                                        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-2">
                                            <div class="flex flex-wrap gap-2">
                                                <button type="button"
                                                    class="search-filter-btn px-3 py-1 text-xs bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition">
                                                    Produk
                                                </button>
                                                <button type="button"
                                                    class="search-filter-btn px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition">
                                                    Transaksi
                                                </button>
                                                <button type="button"
                                                    class="search-filter-btn px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition">
                                                    Supplier
                                                </button>
                                                <button type="button"
                                                    class="search-filter-btn px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full hover:bg-yellow-200 transition">
                                                    Customer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Desktop Navigation Links -->
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-1">
                            <!-- Dashboard -->
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition duration-150">
                                Dashboard
                            </a>

                            <!-- Products Dropdown -->
                            <div class="relative flex items-center" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('products.*') || request()->routeIs('categories.*') ? 'text-purple-600' : 'text-gray-500 hover:text-gray-700' }} transition duration-150">
                                    Produk
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 left-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                    style="display: none;">
                                    <div class="py-1">
                                        <a href="{{ route('products.index') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                            Daftar Produk
                                        </a>
                                        <a href="{{ route('categories.index') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                            Kategori
                                        </a>
                                        @if (auth()->user()->isAdmin())
                                            <a href="{{ route('products.create') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                                Tambah Produk
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Transactions Dropdown -->
                            <div class="relative flex items-center" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('transactions.*') ? 'text-purple-600' : 'text-gray-500 hover:text-gray-700' }} transition duration-150">
                                    Transaksi
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 left-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                    style="display: none;">
                                    <div class="py-1">
                                        <a href="{{ route('transactions.stock-in') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                            📥 Barang Masuk
                                        </a>
                                        <a href="{{ route('transactions.stock-out') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                            📤 Barang Keluar
                                        </a>
                                        <a href="{{ route('transactions.history') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                            📜 Riwayat Transaksi
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Reports (Admin Only) -->
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('reports.index') }}"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('reports.*') ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-700' }} transition duration-150">
                                    Laporan
                                </a>

                                <!-- Master Data Dropdown -->
                                <div class="relative flex items-center" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('suppliers.*') || request()->routeIs('customers.*') || request()->routeIs('users.*') ? 'text-purple-600' : 'text-gray-500 hover:text-gray-700' }} transition duration-150">
                                        Master Data
                                        <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute z-50 left-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                        style="display: none;">
                                        <div class="py-1">
                                            <a href="{{ route('suppliers.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                                Supplier
                                            </a>
                                            <a href="{{ route('customers.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                                Customer
                                            </a>
                                            <a href="{{ route('users.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                                User Management
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- RIGHT SIDE: Mobile Search, Notifications & User Menu -->
                    <div class="flex items-center space-x-3">
                        <!-- MOBILE SEARCH BUTTON -->
                        <div class="lg:hidden flex items-center">
                            <button @click="searchOpen = !searchOpen"
                                class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Notification Button -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition-colors duration-200 hover:bg-gray-100 rounded-full">
                                <span class="sr-only">View notifications</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @php
                                    $lowStockProducts = \App\Models\Product::where(
                                        'stock',
                                        '<=',
                                        \DB::raw('min_stock'),
                                    )->get();
                                    $lowStockCount = $lowStockProducts->count();
                                @endphp
                                @if ($lowStockCount > 0)
                                    <span
                                        class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold leading-none text-white bg-red-500 rounded-full transform translate-x-1 -translate-y-1">
                                        {{ $lowStockCount > 9 ? '9+' : $lowStockCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Notification Dropdown -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="absolute right-0 mt-2 w-80 md:w-96 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                style="display: none;">

                                <div class="rounded-lg shadow-xs overflow-hidden">
                                    <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-4">
                                        <h3 class="text-sm font-semibold">📦 Notifikasi Stok</h3>
                                        <p class="text-xs mt-1 opacity-90">{{ $lowStockCount }} produk perlu restock
                                        </p>
                                    </div>

                                    <div class="max-h-96 overflow-y-auto">
                                        @forelse($lowStockProducts as $product)
                                            <a href="{{ route('products.show', $product) }}"
                                                class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-shrink-0">
                                                        <div
                                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-red-100 flex items-center justify-center">
                                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $product->name }}</p>
                                                        <p class="text-xs text-gray-500">
                                                            Stok: <span
                                                                class="font-semibold {{ $product->stock == 0 ? 'text-red-600' : 'text-yellow-600' }}">
                                                                {{ $product->stock }} {{ $product->unit }}
                                                            </span>
                                                            (Min: {{ $product->min_stock }})
                                                        </p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        @if ($product->stock == 0)
                                                            <span
                                                                class="inline-flex items-center px-1.5 py-0.5 sm:px-2 rounded text-xs font-medium bg-red-100 text-red-800">
                                                                Habis
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center px-1.5 py-0.5 sm:px-2 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                Menipis
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="px-4 py-8 text-center">
                                                <div
                                                    class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-green-600" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-gray-600">Semua stok aman!</p>
                                                <p class="text-xs text-gray-500 mt-1">Tidak ada produk yang perlu
                                                    restock</p>
                                            </div>
                                        @endforelse
                                    </div>

                                    @if ($lowStockCount > 0)
                                        <div class="bg-gray-50 px-4 py-3">
                                            <a href="{{ route('products.index') }}"
                                                class="text-sm text-center block w-full text-blue-600 hover:text-blue-800 font-medium transition-colors duration-150">
                                                Lihat Semua Produk →
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Dropdown (Desktop) -->
                        <div class="hidden sm:flex sm:items-center">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false"
                                    class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150">
                                    <div
                                        class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center mr-2">
                                        <span
                                            class="text-purple-600 font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    {{ Auth::user()->name }}
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                    style="display: none;">
                                    <div class="py-1">
                                        <a href="{{ route('profile.edit') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                            Profile
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                                Log Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Menu Button -->
                        <div class="flex items-center sm:hidden">
                            <button @click="mobileMenuOpen = !mobileMenuOpen"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500 transition duration-150">
                                <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg class="h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MOBILE SEARCH PANEL -->
            <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="lg:hidden border-b border-gray-200 bg-gray-50 p-4" style="display: none;">
                <form action="{{ route('search.global') }}" method="GET">
                    <div class="relative">
                        <input type="search" name="q" value="{{ request('q') }}"
                            placeholder="Cari produk, transaksi, supplier..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            autofocus>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Mobile Menu Panel -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="sm:hidden bg-white border-b border-gray-200" style="display: none;">

                <!-- Mobile User Info -->
                <div class="px-4 pt-4 pb-3 border-b border-gray-200 bg-purple-50">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-purple-600 flex items-center justify-center">
                            <span
                                class="text-white font-semibold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Links -->
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}"
                        class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-purple-500 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150">
                        Dashboard
                    </a>

                    <!-- Products Section -->
                    <div class="border-t border-gray-200 pt-2">
                        <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Produk
                        </div>
                        <a href="{{ route('products.index') }}"
                            class="block pl-6 pr-4 py-2 border-l-4 {{ request()->routeIs('products.index') ? 'border-purple-500 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium">
                            Daftar Produk
                        </a>
                        <a href="{{ route('categories.index') }}"
                            class="block pl-6 pr-4 py-2 border-l-4 {{ request()->routeIs('categories.*') ? 'border-purple-500 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium">
                            Kategori
                        </a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('products.create') }}"
                                class="block pl-6 pr-4 py-2 border-l-4 {{ request()->routeIs('products.create') ? 'border-purple-500 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium">
                                Tambah Produk
                            </a>
                        @endif
                    </div>

                    <!-- Transactions Section -->
                    <div class="border-t border-gray-200 pt-2">
                        <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Transaksi
                        </div>
                        <a href="{{ route('transactions.stock-in') }}"
                            class="block pl-6 pr-4 py-2 border-l-4 {{ request()->routeIs('transactions.stock-in') ? 'border-purple-500 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium">
                            📥 Barang Masuk
                        </a>
                        <a href="{{ route('transactions.stock-out') }}"
                            class="block pl-6 pr-4 py-2 border-l-4 {{ request()->routeIs('transactions.stock-out') ? 'border-purple-500 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium">
                            📤 Barang Keluar
                        </a>
                        <a href="{{ route('transactions.history') }}"
                            class="block pl-6 pr-4 py-2 border-l-4 {{ request()->routeIs('transactions.history') ? 'border-purple-500 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium">
                            📜 Riwayat
                        </a>
                    </div>

                    @if (auth()->user()->isAdmin())
                        <!-- Admin Menu -->
                        <div class="border-t border-gray-200 pt-2">
                            <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Admin Menu
                            </div>
                            <a href="{{ route('reports.index') }}"
                                class="block pl-6 pr-4 py-2 border-l-4 {{ request()->routeIs('reports.*') ? 'border-purple-500 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium">
                                📊 Laporan
                            </a>
                            <a href="{{ route('suppliers.index') }}"
                                class="block pl-6 pr-4 py-2 border-l-4 {{ request()->routeIs('suppliers.*') ? 'border-purple-500 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium">
                                Supplier
                            </a>
                            <a href="{{ route('customers.index') }}"
                                class="block pl-6 pr-4 py-2 border-l-4 {{ request()->routeIs('customers.*') ? 'border-purple-500 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium">
                                Customer
                            </a>
                            <a href="{{ route('users.index') }}"
                                class="block pl-6 pr-4 py-2 border-l-4 {{ request()->routeIs('users.*') ? 'border-purple-500 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium">
                                Users
                            </a>
                        </div>
                    @endif

                    <!-- User Actions -->
                    <div class="border-t border-gray-200 pt-2">
                        <a href="{{ route('profile.edit') }}"
                            class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 text-base font-medium">
                            Profile Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 text-base font-medium">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- NAVIGATION BAR END -->

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow animate-fade-in">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="animate-slide-in-up">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts

    <!-- JavaScript untuk keyboard shortcut dan advanced features -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Keyboard shortcut Ctrl+K atau Cmd+K untuk fokus ke search
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.querySelector('input[name="q"]');
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }
            });

            // Show filter options on focus (optional)
            const searchInput = document.querySelector('input[name="q"]');
            const searchFilters = document.getElementById('searchFilters');

            if (searchInput && searchFilters) {
                searchInput.addEventListener('focus', function() {
                    // searchFilters.classList.remove('hidden');
                });

                searchInput.addEventListener('blur', function() {
                    setTimeout(() => {
                        searchFilters.classList.add('hidden');
                    }, 200);
                });
            }
        });
    </script>

    <!-- Alpine.js initialization -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', () => ({
                open: false,
                toggle() {
                    this.open = !this.open
                }
            }))
        })
    </script>
</body>

</html>
