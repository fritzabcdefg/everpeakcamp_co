<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display all products in the shop with filters.
     */
    public function show(Request $request)
    {
        $query = Product::with('category', 'stock');

        $search = trim((string) $request->get('search', ''));
        $selectedCategory = $request->get('category', '');
        $selectedType = trim((string) $request->get('type', ''));
        $minPrice = $request->get('min_price', '');
        $maxPrice = $request->get('max_price', '');

        // Handle search
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Handle category filter
        if ($selectedCategory !== '') {
            $query->where('category_id', $selectedCategory);
        }

        // Handle type filter using category name so structure stays unchanged
        if ($selectedType !== '') {
            $query->whereHas('category', function ($categoryQuery) use ($selectedType) {
                $categoryQuery->where('name', 'like', "%{$selectedType}%");
            });
        }

        // Handle price filter - min and max price
        if ($minPrice !== '') {
            $query->where('sell_price', '>=', (float) $minPrice);
        }

        if ($maxPrice !== '') {
            $query->where('sell_price', '<=', (float) $maxPrice);
        }

        // Handle sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        if (in_array($sortBy, ['name', 'sell_price', 'created_at'])) {
            $query->orderBy($sortBy, in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc');
        } else {
            $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12)->appends($request->query());
        $categories = Category::orderBy('name')->get();

        // Get min and max prices for filter UI
        $priceStats = Product::selectRaw('MIN(sell_price) as min_price, MAX(sell_price) as max_price')->first();

        return view('shop.show', [
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'selectedCategory' => $selectedCategory,
            'selectedType' => $selectedType,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'priceStats' => $priceStats,
        ]);
    }
}
