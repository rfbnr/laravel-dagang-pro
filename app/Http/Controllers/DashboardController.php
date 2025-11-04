<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoices;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalStock = Product::count();
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'reorder_level')->count();
        $totalCustomers = Customer::count();
        $totalSuppliers = Supplier::count();

        $todaysSales = Invoices::where('type', 'sale')
            ->whereDate('date', now())
            ->where('status', 'paid') 
            ->sum('total_amount');


        return view('dashboard', compact(
            'totalUsers',
            'totalStock',
            'lowStockProducts',
            'totalCustomers',
            'totalSuppliers',
            'todaysSales'
        ));
    }
}
