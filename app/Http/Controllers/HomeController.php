<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $perPage = 12;

        if ($search === '') {
            $products = Product::with('category', 'stock')
                ->whereNull('deleted_at')
                ->paginate($perPage)
                ->withQueryString();
        } else {
            $products = Product::search($search)
                ->query(fn ($query) => $query->with('category', 'stock')->whereNull('deleted_at'))
                ->paginate($perPage)
                ->withQueryString();
        }

        $categories = Category::orderBy('name')->get();

        return view('home', [
            'products' => $products,
            'search' => $search,
            'categories' => $categories,
        ]);
    }
}
