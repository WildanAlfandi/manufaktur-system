<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🔔 Pengaturan Notifikasi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('settings.notifications.update') }}">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-semibold mb-4">Preferensi Email Notifikasi</h3>

                        <div class="space-y-4">
                            <!-- Low Stock Notifications -->
                            <div class="flex items-center justify-between p-4 border rounded-lg">
                                <div>
                                    <h4 class="font-medium">Peringatan Stok Menipis</h4>
                                    <p class="text-sm text-gray-500">Terima email saat stok produk mencapai batas
                                        minimum</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="low_stock_notification" value="1"
                                        class="sr-only peer"
                                        {{ auth()->user()->notification_settings['low_stock'] ?? true ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>

                            <!-- Transaction Notifications -->
                            <div class="flex items-center justify-between p-4 border rounded-lg">
                                <div>
                                    <h4 class="font-medium">Notifikasi Transaksi</h4>
                                    <p class="text-sm text-gray-500">Terima email untuk setiap transaksi baru</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="transaction_notification" value="1"
                                        class="sr-only peer"
                                        {{ auth()->user()->notification_settings['transaction'] ?? true ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>

                            <!-- Daily Report -->
                            <div class="flex items-center justify-between p-4 border rounded-lg">
                                <div>
                                    <h4 class="font-medium">Laporan Harian</h4>
                                    <p class="text-sm text-gray-500">Terima ringkasan aktivitas setiap hari pukul 20:00
                                    </p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="daily_report" value="1" class="sr-only peer"
                                        {{ auth()->user()->notification_settings['daily_report'] ?? true ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>

                    <!-- Test Email Section -->
                    <div class="mt-8 pt-8 border-t">
                        <h3 class="text-lg font-semibold mb-4">Test Email Notifikasi</h3>
                        <p class="text-sm text-gray-600 mb-4">Klik tombol di bawah untuk mengirim test email ke:
                            <strong>{{ auth()->user()->email }}</strong></p>

                        <form method="POST" action="{{ route('settings.notifications.test') }}">
                            @csrf
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                📧 Kirim Test Email
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">📬 Notifikasi Terakhir</h3>

                    <div class="space-y-3">
                        @forelse(auth()->user()->notifications()->take(10)->get() as $notification)
                            <div
                                class="flex items-start space-x-3 p-3 {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50' }} rounded-lg">
                                <div class="flex-shrink-0">
                                    @if ($notification->data['type'] == 'low_stock')
                                        <div
                                            class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                    @elseif($notification->data['type'] == 'transaction')
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">{{ $notification->data['message'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                @if (!$notification->read_at)
                                    <form method="POST"
                                        action="{{ route('notifications.mark-as-read', $notification->id) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                            Mark as read
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">Belum ada notifikasi</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
