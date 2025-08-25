<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'product_id',
        'type',
        'quantity',
        'price',
        'total',
        'transaction_date',
        'user_id',
        'supplier_id',
        'customer_id',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'quantity' => 'integer',
        'transaction_date' => 'date',
    ];

    // Boot method to generate invoice number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->invoice_number)) {
                $transaction->invoice_number = self::generateInvoiceNumber($transaction->type);
            }

            // Calculate total if not set
            if (empty($transaction->total)) {
                $transaction->total = $transaction->quantity * $transaction->price;
            }
        });

        // Update product stock after transaction
        static::created(function ($transaction) {
            $product = $transaction->product;

            if ($transaction->type === 'in') {
                $product->increment('stock', $transaction->quantity);
            } else {
                $product->decrement('stock', $transaction->quantity);
            }
        });
    }

    // Generate unique invoice number
    public static function generateInvoiceNumber($type)
    {
        $prefix = $type === 'in' ? 'IN' : 'OUT';
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;

        return sprintf('%s-%s-%04d', $prefix, $date, $count);
    }

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
