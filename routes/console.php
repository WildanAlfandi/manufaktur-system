<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ===== TAMBAHKAN SCHEDULING DI BAWAH INI =====

// Send daily report at 8 PM every day
Schedule::command('report:daily')->dailyAt('20:00');

// Check low stock every morning at 8 AM
Schedule::call(function () {
    $lowStockProducts = Product::where('stock', '<=', \DB::raw('min_stock'))->get();

    if ($lowStockProducts->count() > 0) {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new LowStockNotification(null, $lowStockProducts));
        }
    }
})->dailyAt('08:00');
