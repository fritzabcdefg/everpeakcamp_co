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
    public function index()
    {
        $products = Product::with('category', 'stock')->where('deleted_at', null)->paginate(12);
        return view('home', ['products' => $products]);
    }
}
