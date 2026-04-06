<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        $flashSaleProducts = Product::with(['category', 'images' => function ($q) {
            $q->where('is_primary', true);
        }])
            ->where('is_active', true)
            ->whereNotNull('compare_price')
            ->where('compare_price', '>', DB::raw('price'))
            ->latest()
            ->take(4)
            ->get();

        $latestProducts = Product::with(['category', 'images' => function ($q) {
            $q->where('is_primary', true);
        }])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', compact('categories', 'flashSaleProducts', 'latestProducts'));
    }

    public function collections(Request $request)
    {
        $query = Product::with(['category', 'images']);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::withCount('products')->get();

        return view('user.collections.index', compact('categories', 'products'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'images'])->where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::with(['category', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('user.collections.show', compact('product', 'relatedProducts'));
    }
}
