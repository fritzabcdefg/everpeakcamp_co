<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category', 'stock')->paginate(15);
        return view('products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Product $product)
    {
        // Laravel automatically finds the product by its ID!
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,category_id',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('img_path')) {
            $validated['img_path'] = $request->file('img_path')->store('products', 'public');
        }

        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category', 'images', 'reviews', 'stock');
        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'cost_price' => 'sometimes|numeric|min:0',
            'sell_price' => 'sometimes|numeric|min:0',
            'category_id' => 'nullable|exists:categories,category_id',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('img_path')) {
            // Delete old image if exists
            if ($product->img_path) {
                Storage::disk('public')->delete($product->img_path);
            }
            $validated['img_path'] = $request->file('img_path')->store('products', 'public');
        }

        $product->update($validated);
        return redirect()->route('products.show', $product)->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->img_path) {
            Storage::disk('public')->delete($product->img_path);
        }
        
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
