<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Sales;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Sales::sum('total_amount');
        $totalOrders = Sales::count();
        $lowStockProducts = Products::where('quantity', '<=', 10)->get();

        $recentOrders = Sales::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalSales',
            'totalOrders',
            'lowStockProducts',
            'recentOrders'
        ));
    }
}
