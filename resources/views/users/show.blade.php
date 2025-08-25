<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail User: {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- User Profile Card -->
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-center">
                                <div
                                    class="mx-auto h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center mb-4">
                                    <span
                                        class="text-3xl font-bold text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <h3 class="text-lg font-semibold">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>

                                @if ($user->role === 'admin')
                                    <span
                                        class="mt-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Admin
                                    </span>
                                @else
                                    <span
                                        class="mt-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Staff
                                    </span>
                                @endif
                            </div>

                            <div class="mt-6 border-t pt-6">
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">Terdaftar:</dt>
                                        <dd class="text-sm font-medium">{{ $user->created_at->format('d/m/Y') }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">Last Login:</dt>
                                        <dd class="text-sm font-medium">
                                            {{ $stats['last_login'] ? $stats['last_login']->format('d/m/Y H:i') : 'Never' }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">Status:</dt>
                                        <dd class="text-sm font-medium text-green-600">Active</dd>
                                    </div>
                                </dl>
                            </div>

                            @if ($user->id !== auth()->id())
                                <div class="mt-6 space-y-2">
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block">
                                        Edit User
                                    </a>
                                    <form action="{{ route('users.reset-password', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Reset password untuk {{ $user->name }}?')">
                                            Reset Password
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stats & Activities -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Statistics -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Statistik</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_transactions'] }}
                                    </div>
                                    <div class="text-sm text-gray-500">Total Transaksi</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $stats['transactions_in'] }}
                                    </div>
                                    <div class="text-sm text-gray-500">Barang Masuk</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-red-600">{{ $stats['transactions_out'] }}</div>
                                    <div class="text-sm text-gray-500">Barang Keluar</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Transaksi Terakhir</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Invoice</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Tanggal</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Tipe</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Produk</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($user->transactions as $transaction)
                                            <tr>
                                                <td class="px-4 py-2 text-sm">{{ $transaction->invoice_number }}</td>
                                                <td class="px-4 py-2 text-sm">
                                                    {{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                                <td class="px-4 py-2 text-sm">
                                                    @if ($transaction->type == 'in')
                                                        <span class="text-green-600">Masuk</span>
                                                    @else
                                                        <span class="text-red-600">Keluar</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 text-sm">{{ $transaction->product->name }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $transaction->quantity }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-2 text-center text-sm text-gray-500">
                                                    Belum ada transaksi
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Log -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Activity Log</h3>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @forelse($user->activityLogs as $log)
                                    <div class="flex items-start space-x-3 text-sm">
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-gray-900">
                                                {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                                @if ($log->model)
                                                    - {{ $log->model }}
                                                @endif
                                            </p>
                                            <p class="text-gray-500 text-xs">{{ $log->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada aktivitas</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
