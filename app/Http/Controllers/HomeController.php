<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalCustomers = Customer::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        $recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalOrders',
            'totalCustomers',
            'totalRevenue',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
