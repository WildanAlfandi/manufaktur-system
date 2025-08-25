<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use App\Notifications\DailyReportNotification;
use Carbon\Carbon;

class SendDailyReport extends Command
{
    protected $signature = 'report:daily';
    protected $description = 'Send daily report email to all admins';

    public function handle()
    {
        $this->info('Generating daily report...');

        // Collect report data
        $reportData = [
            'total_transactions' => Transaction::whereDate('created_at', Carbon::today())->count(),
            'total_in' => Transaction::where('type', 'in')->whereDate('created_at', Carbon::today())->count(),
            'total_out' => Transaction::where('type', 'out')->whereDate('created_at', Carbon::today())->count(),
            'total_value' => Transaction::whereDate('created_at', Carbon::today())->sum('total'),
            'low_stock_products' => Product::where('stock', '<=', \DB::raw('min_stock'))->get(),
            'top_products' => Transaction::where('type', 'out')
                ->whereDate('created_at', Carbon::today())
                ->select('product_id', \DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->take(5)
                ->with('product')
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'name' => $item->product->name,
                        'total_quantity' => $item->total_quantity,
                        'unit' => $item->product->unit
                    ];
                })
        ];

        // Send to all admins
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new DailyReportNotification($reportData));
            $this->info('Report sent to: ' . $admin->email);
        }

        $this->info('Daily reports sent successfully!');
        return 0;
    }
}
