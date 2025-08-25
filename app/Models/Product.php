<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'price',
        'stock',
        'min_stock',
        'description',
        'image',
        'barcode',
        'unit',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
    ];

    // Relationship: Product belongs to category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: Product has many transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Check if stock is below minimum
    public function isLowStock()
    {
        return $this->stock <= $this->min_stock;
    }

    // Get stock status
    public function getStockStatusAttribute()
    {
        if ($this->stock == 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= $this->min_stock) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }
}
