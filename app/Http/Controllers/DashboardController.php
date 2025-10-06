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
        $lowStockProducts = Product::where('stock', '<', 10)->count();
        $inventoryValue = Product::sum(DB::raw('price * stock'));

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalUsers',
            'lowStockProducts',
            'inventoryValue'
        ));
    }
}
