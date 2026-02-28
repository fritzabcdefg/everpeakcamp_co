<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display all products in the shop.
     */
    public function show(Request $request)
    {
        $products = Product::with('category', 'stock');

        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $products->where('name', 'like', "%{$search}%")
                     ->orWhere('description', 'like', "%{$search}%");
        }

        // Handle category filter
        if ($request->has('category') && !empty($request->category)) {
            $products->where('category_id', $request->category);
        }

        $products = $products->paginate(12);
        $categories = \App\Models\Category::all();

        return view('shop.show', [
            'products' => $products,
            'categories' => $categories,
            'search' => $request->search ?? '',
            'selectedCategory' => $request->category ?? '',
        ]);
    }
}
