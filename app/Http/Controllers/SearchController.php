<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return redirect()->back();
        }

        $results = [
            'products' => $this->searchProducts($query),
            'transactions' => $this->searchTransactions($query),
            'suppliers' => $this->searchSuppliers($query),
            'customers' => $this->searchCustomers($query),
            'users' => auth()->user()->isAdmin() ? $this->searchUsers($query) : collect(),
        ];

        $totalResults = collect($results)->sum(function ($items) {
            return $items->count();
        });

        return view('search.results', compact('results', 'query', 'totalResults'));
    }

    private function searchProducts($query)
    {
        return Product::with('category')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->orWhere('barcode', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->take(10)
            ->get();
    }

    private function searchTransactions($query)
    {
        return Transaction::with(['product', 'user'])
            ->where('invoice_number', 'LIKE', "%{$query}%")
            ->orWhere('notes', 'LIKE', "%{$query}%")
            ->orWhereHas('product', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->take(10)
            ->get();
    }

    private function searchSuppliers($query)
    {
        return Supplier::where('name', 'LIKE', "%{$query}%")
            ->orWhere('contact_person', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->take(10)
            ->get();
    }

    private function searchCustomers($query)
    {
        return Customer::where('name', 'LIKE', "%{$query}%")
            ->orWhere('contact_person', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->take(10)
            ->get();
    }

    private function searchUsers($query)
    {
        return User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->take(10)
            ->get();
    }
}
