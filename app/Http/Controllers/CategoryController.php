<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('categories.index');
    }

    /**
     * Get categories for DataTable
     */
    public function datatable(Request $request)
    {
        try {
            \Log::info('CategoryController::datatable called - User: ' . (auth()->check() ? auth()->user()->id : 'NOT_AUTHENTICATED'));
            
            if (!auth()->check()) {
                \Log::warning('Unauthorized datatable access - user not authenticated');
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            if (auth()->user()->role !== 'admin') {
                \Log::warning('Unauthorized datatable access - user not admin');
                return response()->json(['error' => 'Forbidden'], 403);
            }
            
            $query = Category::select('categories.*')->selectRaw('category_id as id')->with('products')->orderBy('categories.category_id', 'desc');

            return DataTables::of($query)
                ->setRowId('category_id')
                ->addColumn('product_count', function ($category) {
                    return '<span class="badge bg-info">' . $category->products->count() . '</span>';
                })
                ->addColumn('actions', function ($category) {
                    return $this->renderActions($category);
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                          ->orWhere('description', 'like', "%{$keyword}%");
                })
                ->rawColumns(['product_count', 'actions'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('DataTable error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Render action buttons
     */
    private function renderActions(Category $category)
    {
        $actions = '<a href="' . route('categories.show', $category) . '" class="btn btn-sm btn-info me-1" title="View"><i class="fas fa-eye"></i> View</a>';
        $actions .= '<a href="' . route('categories.edit', $category) . '" class="btn btn-sm btn-warning me-1" title="Edit"><i class="fas fa-edit"></i> Edit</a>';
        $actions .= '<form action="' . route('categories.destroy', $category) . '" method="POST" style="display:inline;">';
        $actions .= '<input type="hidden" name="_method" value="DELETE">';
        $actions .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
        $actions .= '<button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash"></i> Delete</button>';
        $actions .= '</form>';
        return $actions;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('products');
        return view('categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $category->category_id . ',category_id',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);
        return redirect()->route('categories.show', $category)->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }
}
