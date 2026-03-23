<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Stock;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('products.index', ['categories' => $categories]);
    }

    /**
     * Get products data for DataTables (API endpoint)
     */
    public function datatable(Request $request)
    {
        $query = Product::with('category', 'stock', 'images');
        
        // Include soft deleted products if requested
        if ($request->get('include_trashed') == 'true') {
            $query->withTrashed();
        }

        // Search functionality
        if ($request->has('search') && $request->get('search') != '') {
            $search = $request->get('search')['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        
        // Pagination
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        
        $products = $query->skip($start)
                         ->take($length)
                         ->get();

        $data = $products->map(function($product) {
            $stock = $product->stock->sum('quantity');
            $mainImage = $product->img_path ? Storage::url($product->img_path) : asset('images/no-image.png');
            $photoCount = $product->images->count();
            
            return [
                'product_id' => $product->product_id,
                'name' => $product->name,
                'image' => '<img src="' . $mainImage . '" alt="' . $product->name . '" width="50" height="50" class="img-thumbnail">',
                'category' => $product->category ? $product->category->name : 'Uncategorized',
                'cost_price' => number_format($product->cost_price, 2),
                'sell_price' => number_format($product->sell_price, 2),
                'stock' => $stock > 0 ? '<span class="badge bg-success">' . $stock . '</span>' : '<span class="badge bg-danger">Out of Stock</span>',
                'photos' => '<span class="badge bg-info">' . $photoCount . '</span>',
                'status' => $product->deleted_at ? '<span class="badge bg-secondary">Deleted</span>' : '<span class="badge bg-success">Active</span>',
                'actions' => $this->renderActions($product),
            ];
        });

        return response()->json([
            'draw' => $request->get('draw', 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }

    /**
     * Render action buttons
     */
    private function renderActions($product)
    {
        $actions = '<div class="btn-group btn-group-sm" role="group" aria-label="Product actions">';
        
        $actions .= '<a href="' . route('products.show', $product) . '" class="btn btn-info" title="View"><i class="fas fa-eye"></i></a>';
        
        if (auth()->check() && auth()->user()->role === 'admin') {
            $actions .= '<a href="' . route('products.edit', $product) . '" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>';
            
            if ($product->deleted_at) {
                $actions .= '<a href="' . route('products.restore', $product->product_id) . '" class="btn btn-success" title="Restore"><i class="fas fa-undo"></i></a>';
            }
            
            $actions .= '<button type="button" class="btn btn-danger" title="Delete" onclick="if(confirm(\'Are you sure?\')) { document.getElementById(\'delete-product-' . $product->product_id . '\').submit(); }"><i class="fas fa-trash"></i></button>';
        }
        
        $actions .= '</div>';

        if (auth()->check() && auth()->user()->role === 'admin') {
            $actions .= '<form id="delete-product-' . $product->product_id . '" action="' . route('products.destroy', $product) . '" method="POST" style="display:none;">';
            $actions .= '<input type="hidden" name="_method" value="DELETE">';
            $actions .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
            $actions .= '</form>';
        }

        return $actions;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255|regex:/^[a-zA-Z0-9\s\-&\/.,()]+$/',
            'description' => 'required|string|min:10|max:5000',
            'cost_price' => 'required|numeric|min:0|max:999999.99',
            'sell_price' => 'required|numeric|min:0|max:999999.99|gte:cost_price',
            'category_id' => 'nullable|exists:categories,category_id',
            'stocks' => 'nullable|integer|min:0',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100',
        ], [
            'name.required' => 'Product name is required.',
            'name.min' => 'Product name must be at least 3 characters.',
            'name.max' => 'Product name cannot exceed 255 characters.',
            'name.regex' => 'Product name contains invalid characters.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 10 characters.',
            'description.max' => 'Description cannot exceed 5000 characters.',
            'cost_price.required' => 'Cost price is required.',
            'cost_price.numeric' => 'Cost price must be a valid number.',
            'cost_price.min' => 'Cost price cannot be negative.',
            'cost_price.max' => 'Cost price is too high.',
            'sell_price.required' => 'Selling price is required.',
            'sell_price.numeric' => 'Selling price must be a valid number.',
            'sell_price.min' => 'Selling price cannot be negative.',
            'sell_price.max' => 'Selling price is too high.',
            'sell_price.gte' => 'Selling price must be greater than or equal to cost price.',
            'category_id.exists' => 'Selected category does not exist.',
            'img_path.image' => 'Main image must be a valid image file.',
            'img_path.mimes' => 'Main image must be JPG, PNG or GIF format.',
            'img_path.max' => 'Main image cannot exceed 2MB.',
            'img_path.dimensions' => 'Main image must be at least 100x100 pixels.',
            'images.*.image' => 'Gallery images must be valid image files.',
            'images.*.mimes' => 'Gallery images must be JPG, PNG or GIF format.',
            'images.*.max' => 'Each gallery image cannot exceed 2MB.',
            'images.*.dimensions' => 'Gallery images must be at least 100x100 pixels.',
        ]);

        // Handle main product image
        if ($request->hasFile('img_path')) {
            $validated['img_path'] = $request->file('img_path')->store('products', 'public');
        }

        $product = Product::create($validated);

        if ($request->filled('stocks')) {
            Stock::create([
                'product_id' => $product->product_id,
                'quantity' => (int) $request->input('stocks'),
            ]);
        }

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->product_id,
                    'img_path' => $path,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category', 'images', 'reviews.user', 'stock');
        
        // Get user's review if exists
        $userReview = null;
        if (auth()->check()) {
            $userReview = $product->reviews()->where('user_id', auth()->id())->first();
        }
        
        // Check if user is admin (accessing via admin panel)
        if (auth()->check() && auth()->user()->role === 'admin') {
            return view('products.show', ['product' => $product]);
        }
        
        // Otherwise show customer-facing view with reviews
        return view('products.customer-detail', ['product' => $product, 'userReview' => $userReview]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $images = $product->images;
        return view('products.edit', ['product' => $product, 'categories' => $categories, 'images' => $images]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255|regex:/^[a-zA-Z0-9\s\-&\/.,()]+$/',
            'description' => 'required|string|min:10|max:5000',
            'cost_price' => 'required|numeric|min:0|max:999999.99',
            'sell_price' => 'required|numeric|min:0|max:999999.99|gte:cost_price',
            'category_id' => 'nullable|exists:categories,category_id',
            'stocks' => 'nullable|integer|min:0',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100',
        ], [
            'name.required' => 'Product name is required.',
            'name.min' => 'Product name must be at least 3 characters.',
            'name.max' => 'Product name cannot exceed 255 characters.',
            'name.regex' => 'Product name contains invalid characters.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 10 characters.',
            'description.max' => 'Description cannot exceed 5000 characters.',
            'cost_price.required' => 'Cost price is required.',
            'cost_price.numeric' => 'Cost price must be a valid number.',
            'cost_price.min' => 'Cost price cannot be negative.',
            'cost_price.max' => 'Cost price is too high.',
            'sell_price.required' => 'Selling price is required.',
            'sell_price.numeric' => 'Selling price must be a valid number.',
            'sell_price.min' => 'Selling price cannot be negative.',
            'sell_price.max' => 'Selling price is too high.',
            'sell_price.gte' => 'Selling price must be greater than or equal to cost price.',
            'category_id.exists' => 'Selected category does not exist.',
            'img_path.image' => 'Main image must be a valid image file.',
            'img_path.mimes' => 'Main image must be JPG, PNG or GIF format.',
            'img_path.max' => 'Main image cannot exceed 2MB.',
            'img_path.dimensions' => 'Main image must be at least 100x100 pixels.',
            'images.*.image' => 'Gallery images must be valid image files.',
            'images.*.mimes' => 'Gallery images must be JPG, PNG or GIF format.',
            'images.*.max' => 'Each gallery image cannot exceed 2MB.',
            'images.*.dimensions' => 'Gallery images must be at least 100x100 pixels.',
        ]);

        // Handle main product image
        if ($request->hasFile('img_path')) {
            if ($product->img_path) {
                Storage::disk('public')->delete($product->img_path);
            }
            $validated['img_path'] = $request->file('img_path')->store('products', 'public');
        }

        $product->update($validated);

        if ($request->filled('stocks')) {
            $stock = $product->stock()->first();
            if ($stock) {
                $stock->update(['quantity' => (int) $request->input('stocks')]);
            } else {
                $product->stock()->create(['quantity' => (int) $request->input('stocks')]);
            }
        }

        // Handle additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->product_id,
                    'img_path' => $path,
                ]);
            }
        }

        return redirect()->route('products.show', $product)->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }

    /**
     * Restore a soft-deleted product.
     */
    public function restore($id)
    {
        $product = Product::withTrashed()->find($id);
        
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }

        $product->restore();
        return redirect()->route('products.index')->with('success', 'Product restored successfully');
    }

    /**
     * Delete product image
     */
    public function deleteImage($imageId)
    {
        $image = ProductImage::find($imageId);
        
        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        Storage::disk('public')->delete($image->img_path);
        $image->delete();

        return response()->json(['success' => 'Image deleted successfully']);
    }

    /**
     * Import products from Excel
     */
    public function importForm()
    {
        return view('products.import');
    }

    /**
     * Handle Excel import
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->route('products.index')->with('success', 'Products imported successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
