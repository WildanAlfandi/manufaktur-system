<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\User;
use App\Models\ActivityLog;
use App\Notifications\TransactionNotification;
use App\Notifications\LowStockNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Barang Masuk
    public function stockIn()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('transactions.stock-in', compact('products', 'suppliers'));
    }

    public function storeStockIn(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'transaction_date' => 'required|date',
            'notes' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            // Generate invoice number (tambahan baru)
            $invoiceNumber = 'IN-' . date('Ymd') . '-' . str_pad(Transaction::where('type', 'in')->count() + 1, 4, '0', STR_PAD_LEFT);

            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber, // tambahan baru
                'product_id' => $validated['product_id'],
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'total' => $validated['quantity'] * $validated['price'],
                'supplier_id' => $validated['supplier_id'],
                'transaction_date' => $validated['transaction_date'],
                'user_id' => auth()->id(),
                'notes' => $validated['notes'],
            ]);

            // Update product stock (already handled by Transaction model boot method)

            // Send notification to admin (tambahan baru)
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new TransactionNotification($transaction, 'created'));
            }

            // Log activity
            ActivityLog::log('stock_in', 'Transaction', $transaction->id, null, $transaction->toArray());

            DB::commit();

            return redirect()->route('transactions.history')
                ->with('success', 'Barang masuk berhasil dicatat! Email notifikasi telah dikirim.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Barang Keluar
    public function stockOut()
    {
        $products = Product::where('stock', '>', 0)->get();
        $customers = Customer::all();
        return view('transactions.stock-out', compact('products', 'customers'));
    }

    public function storeStockOut(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
            'transaction_date' => 'required|date',
            'notes' => 'nullable',
        ]);

        // Check stock availability
        $product = Product::find($validated['product_id']);
        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $product->stock);
        }

        DB::beginTransaction();
        try {
            // Generate invoice number (tambahan baru)
            $invoiceNumber = 'OUT-' . date('Ymd') . '-' . str_pad(Transaction::where('type', 'out')->count() + 1, 4, '0', STR_PAD_LEFT);

            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber, // tambahan baru
                'product_id' => $validated['product_id'],
                'type' => 'out',
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'total' => $validated['quantity'] * $validated['price'],
                'customer_id' => $validated['customer_id'],
                'transaction_date' => $validated['transaction_date'],
                'user_id' => auth()->id(),
                'notes' => $validated['notes'],
            ]);

            // Update product stock (already handled by Transaction model boot method)

            // Check if product is low on stock after transaction (tambahan baru)
            $product->refresh(); // Refresh untuk mendapat stock terbaru
            if ($product->stock <= $product->min_stock) {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new LowStockNotification($product));
                }
            }

            // Send transaction notification (tambahan baru)
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new TransactionNotification($transaction, 'created'));
            }

            // Log activity
            ActivityLog::log('stock_out', 'Transaction', $transaction->id, null, $transaction->toArray());

            DB::commit();

            return redirect()->route('transactions.history')
                ->with('success', 'Barang keluar berhasil dicatat! Email notifikasi telah dikirim.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // History
    public function history()
    {
        $transactions = Transaction::with(['product', 'user', 'supplier', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('transactions.history', compact('transactions'));
    }
}
