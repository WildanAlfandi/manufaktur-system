<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Get date range (default: current month)
        $startDate = request('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Stock Report
        $stockReport = Product::with('category')
            ->select('products.*', DB::raw('(stock * price) as stock_value'))
            ->get();

        // Transaction Summary
        $transactionSummary = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as total_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as total_out'),
                DB::raw('SUM(CASE WHEN type = "in" THEN total ELSE 0 END) as value_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN total ELSE 0 END) as value_out'),
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Top Products
        $topProducts = Transaction::where('type', 'out')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total) as total_value'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->with('product')
            ->take(10)
            ->get();

        // Category Summary
        $categorySummary = Category::withCount('products')
            ->with([
                'products' => function ($query) {
                    $query
                        ->select(
                            'category_id',
                            DB::raw('SUM(stock) as total_stock'),
                            DB::raw('SUM(stock * price) as total_value'),
                        )
                        ->groupBy('category_id');
                },
            ])
            ->get();

        // Calculate totals
        $totals = [
            'total_products' => Product::count(),
            'total_stock_value' => Product::sum(DB::raw('stock * price')),
            'low_stock_count' => Product::where('stock', '<=', DB::raw('min_stock'))->count(),
            'out_of_stock_count' => Product::where('stock', 0)->count(),
            'total_transactions' => Transaction::whereBetween('transaction_date', [$startDate, $endDate])->count(),
            'total_in' => Transaction::where('type', 'in')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('quantity'),
            'total_out' => Transaction::where('type', 'out')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('quantity'),
        ];

        return view(
            'reports.index',
            compact(
                'stockReport',
                'transactionSummary',
                'topProducts',
                'categorySummary',
                'totals',
                'startDate',
                'endDate',
            ),
        );
    }

    public function exportStock()
    {
        // You can implement Excel export here using Laravel Excel
        // For now, we'll do CSV export

        $products = Product::with('category')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="stock_report_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Kode',
                'Nama Produk',
                'Kategori',
                'Stok',
                'Min Stok',
                'Satuan',
                'Harga',
                'Nilai Stok',
                'Status',
            ]);

            // Data rows
            foreach ($products as $product) {
                $status =
                    $product->stock == 0 ? 'Habis' : ($product->stock <= $product->min_stock ? 'Menipis' : 'Tersedia');
                fputcsv($file, [
                    $product->code,
                    $product->name,
                    $product->category->name,
                    $product->stock,
                    $product->min_stock,
                    $product->unit,
                    $product->price,
                    $product->stock * $product->price,
                    $status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportTransactions()
    {
        $startDate = request('start_date', Carbon::now()->startOfMonth());
        $endDate = request('end_date', Carbon::now()->endOfMonth());

        $transactions = Transaction::with(['product', 'user', 'supplier', 'customer'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transactions_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Invoice',
                'Tanggal',
                'Tipe',
                'Produk',
                'Jumlah',
                'Harga',
                'Total',
                'Supplier/Customer',
                'User',
                'Catatan',
            ]);

            // Data rows
            foreach ($transactions as $trans) {
                $partner = $trans->type == 'in' ? $trans->supplier?->name : $trans->customer?->name;
                fputcsv($file, [
                    $trans->invoice_number,
                    $trans->transaction_date->format('Y-m-d'),
                    $trans->type == 'in' ? 'Masuk' : 'Keluar',
                    $trans->product->name,
                    $trans->quantity,
                    $trans->price,
                    $trans->total,
                    $partner ?? '-',
                    $trans->user->name,
                    $trans->notes ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
