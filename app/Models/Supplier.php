<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact_person', 'phone', 'email', 'address'];

    // Relationship: Supplier has many transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Get total transactions value
    public function getTotalTransactionsAttribute()
    {
        return $this->transactions()->sum('total');
    }
}
