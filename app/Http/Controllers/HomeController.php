<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $totalSales = Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->sum('total');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $avgRating = Review::avg('rating') ?: 0;

        $lastMonthSales = Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('total');
        $salesGrowth = $lastMonthSales > 0 ? (($totalSales - $lastMonthSales) / $lastMonthSales) * 100 : 100;

        $salesData = Order::select(
            DB::raw('SUM(total) as sum'),
            DB::raw("DATE_FORMAT(created_at, '%M') as month")
        )
            ->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy(DB::raw('MIN(created_at)'), 'ASC') // Perbaikan di sini
            ->get();

        $recentTransactions = Order::with(['user', 'items.product'])
            ->latest()
            ->take(8)->get();

        $topProducts = Product::orderBy('sold_count', 'desc')
            ->take(5)->get();

        return view('home', compact(
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'avgRating',
            'salesGrowth',
            'salesData',
            'recentTransactions',
            'topProducts'
        ));
    }
}
