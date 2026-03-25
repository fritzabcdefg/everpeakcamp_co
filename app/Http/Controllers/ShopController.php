<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display all products in the shop with filters.
     * Supports three search methods:
     * - 'like': LIKE query (8pts)
     * - 'model': Model search method using scopeSearch (10pts)
     * - 'scout': Laravel Scout with pagination (15pts)
     */
    public function show(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        // Handle multiple categories (array of category IDs)
        $selectedCategories = $request->input('category', []);
        if (!is_array($selectedCategories)) {
            $selectedCategories = [];
        }
        $selectedCategories = array_filter(array_map('intval', $selectedCategories));
        
        $minPrice = $request->get('min_price', '');
        $maxPrice = $request->get('max_price', '');

        // Use LIKE search (simplified and reliable)
        $products = $this->searchWithLike($search, $selectedCategories, $minPrice, $maxPrice, $request);

        $categories = Category::orderBy('name')->get();

        // Get min and max prices for filter UI
        $priceStats = Product::selectRaw('MIN(sell_price) as min_price, MAX(sell_price) as max_price')->first();

        return view('shop.show', [
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'selectedCategories' => $selectedCategories,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'sortBy' => $request->get('sort_by', 'name'),
            'sortOrder' => $request->get('sort_order', 'asc'),
            'priceStats' => $priceStats,
        ]);
    }

    /**
     * Search using LIKE queries (8pts)
     */
    private function searchWithLike($search, $selectedCategories, $minPrice, $maxPrice, Request $request)
    {
        $query = Product::with('category', 'stock');

        // Handle search with LIKE
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return $this->applyCommonFilters($query, $selectedCategories, $minPrice, $maxPrice, $request);
    }

    /**
     * Apply common filters (category, price, sorting)
     */
    private function applyCommonFilters($query, $selectedCategories, $minPrice, $maxPrice, Request $request)
    {
        // Handle multiple category filter with OR logic (whereIn)
        if (!empty($selectedCategories)) {
            $query->whereIn('category_id', $selectedCategories);
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

        return $query->paginate(12)->appends($request->query());
    }
}
