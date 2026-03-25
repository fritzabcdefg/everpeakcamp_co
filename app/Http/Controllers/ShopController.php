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
        $selectedCategory = $request->get('category', '');
        $selectedType = trim((string) $request->get('type', ''));
        $minPrice = $request->get('min_price', '');
        $maxPrice = $request->get('max_price', '');
        $searchMethod = $request->get('search_method', 'like'); // Default to LIKE query

        // Determine which search method to use
        if ($search !== '' && $searchMethod === 'scout') {
            $products = $this->searchWithScout($search, $selectedCategory, $selectedType, $minPrice, $maxPrice, $request);
        } elseif ($search !== '' && $searchMethod === 'model') {
            $products = $this->searchWithModel($search, $selectedCategory, $selectedType, $minPrice, $maxPrice, $request);
        } else {
            // Default to LIKE query search
            $products = $this->searchWithLike($search, $selectedCategory, $selectedType, $minPrice, $maxPrice, $request);
        }

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
            'sortBy' => $request->get('sort_by', 'name'),
            'sortOrder' => $request->get('sort_order', 'asc'),
            'priceStats' => $priceStats,
            'searchMethod' => $searchMethod,
        ]);
    }

    /**
     * Search using LIKE queries (8pts)
     */
    private function searchWithLike($search, $selectedCategory, $selectedType, $minPrice, $maxPrice, Request $request)
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

        return $this->applyCommonFilters($query, $selectedCategory, $selectedType, $minPrice, $maxPrice, $request);
    }

    /**
     * Search using model scope search method (10pts)
     */
    private function searchWithModel($search, $selectedCategory, $selectedType, $minPrice, $maxPrice, Request $request)
    {
        $query = Product::with('category', 'stock');

        // Use the scopeSearch method from the model
        if ($search !== '') {
            $query->search($search);
        }

        return $this->applyCommonFilters($query, $selectedCategory, $selectedType, $minPrice, $maxPrice, $request);
    }

    /**
     * Search using Laravel Scout (15pts)
     */
    private function searchWithScout($search, $selectedCategory, $selectedType, $minPrice, $maxPrice, Request $request)
    {
        // Perform Scout search on the Product model
        $productIds = Product::search($search)
            ->keys()
            ->toArray();

        // If no results from Scout, return empty paginated collection
        if (empty($productIds)) {
            return Product::with('category', 'stock')
                ->whereIn('product_id', [])
                ->paginate(12)
                ->appends($request->query());
        }

        // Build query from Scout results
        $query = Product::with('category', 'stock')
            ->whereIn('product_id', $productIds);

        return $this->applyCommonFilters($query, $selectedCategory, $selectedType, $minPrice, $maxPrice, $request);
    }

    /**
     * Apply common filters (category, type, price, sorting)
     */
    private function applyCommonFilters($query, $selectedCategory, $selectedType, $minPrice, $maxPrice, Request $request)
    {
        // Handle category filter
        if ($selectedCategory !== '') {
            $query->where('category_id', $selectedCategory);
        }

        // Handle type filter using category name
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

        return $query->paginate(12)->appends($request->query());
    }
}
