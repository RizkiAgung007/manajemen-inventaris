<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $lowStockProductsCount = Product::where('stock', '<', 10)->count();
        $inventoryValue = Product::sum(DB::raw('price * stock'));

        $categoryDistribution = Category::withCount('products')->orderBy('products_count', 'desc')->get();
        $categoryLabels = $categoryDistribution->pluck('name');
        $categoryData = $categoryDistribution->pluck('products_count');

        $lowestStockProducts = Product::orderBy('stock', 'asc')->take(5)->get();
        $lowestStockLabels = $lowestStockProducts->pluck('name');
        $lowestStockData = $lowestStockProducts->pluck('stock');

        $recentProducts = Product::with('categories')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalUsers',
            'lowStockProductsCount',
            'inventoryValue',
            'categoryLabels',
            'categoryData',
            'lowestStockProducts',
            'lowestStockLabels',
            'lowestStockData',
            'recentProducts'
        ));
    }
}
