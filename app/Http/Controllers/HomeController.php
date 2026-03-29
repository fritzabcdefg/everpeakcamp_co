<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application homepage with multiple search implementations.
     * 
     * Query Parameters:
     * - search: The search keyword
     * - search_type: 'like' (LIKE query), 'model' (Model search scope), 'scout' (Laravel Scout)
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $searchType = $request->query('search_type', 'scout'); // Default to scout
        $perPage = 12;

        if ($search === '') {
            // No search - show all featured products
            $products = Product::with('category', 'stock')
                ->whereNull('deleted_at')
                ->latest('product_id')
                ->paginate($perPage)
                ->withQueryString();
            $searchMethod = null;
        } else {
            // Perform search based on search_type parameter
            match ($searchType) {
                'like' => $this->performLikeSearch($request, $search, $perPage, $products),
                'model' => $this->performModelSearch($request, $search, $perPage, $products),
                'scout' => $this->performScoutSearch($request, $search, $perPage, $products),
                default => $this->performScoutSearch($request, $search, $perPage, $products),
            };
            $searchMethod = $searchType;
        }

        $categories = Category::orderBy('name')->get();

        return view('home', [
            'products' => $products,
            'search' => $search,
            'searchMethod' => $searchMethod,
            'categories' => $categories,
        ]);
    }

    /**
     * Search using LIKE query (8pts) - Direct SQL LIKE query
     */
    private function performLikeSearch(Request $request, string $search, int $perPage, &$products): void
    {
        $products = Product::with('category', 'stock')
            ->whereNull('deleted_at')
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Search using Model scope (10pts) - Using Eloquent model search scope
     */
    private function performModelSearch(Request $request, string $search, int $perPage, &$products): void
    {
        $products = Product::with('category', 'stock')
            ->whereNull('deleted_at')
            ->search($search)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Search using Laravel Scout (15pts) - Full-text search with pagination
     */
    private function performScoutSearch(Request $request, string $search, int $perPage, &$products): void
    {
        $products = Product::search($search)
            ->query(fn ($query) => $query->with('category', 'stock')->whereNull('deleted_at'))
            ->paginate($perPage)
            ->withQueryString();
    }
}
