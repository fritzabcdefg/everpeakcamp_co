@extends('layouts.base')

@section('content')
    <div id="products" class="container-fluid mt-4">
        @include('layouts.flash-messages')
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Outdoor & Camping Gears</h2>
            @if (Auth::check() && Auth::user()->role === 'admin')
                <a class="btn btn-primary" href="{{ route('products.create') }}" role="button">
                    <i class="fas fa-plus"></i> Add New Gear
                </a>
            @endif
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <input type="text" id="productSearch" class="form-control" placeholder="Search products by name...">
            </div>
        </div>

        <div class="table-responsive">
            <table id="productsTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Cost Price</th>
                        <th>Sell Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>#{{ $product->product_id }}</td>
                            <td>
                                @if ($product->img_path)
                                    <img src="{{ Storage::url($product->img_path) }}" alt="{{ $product->name }}" 
                                         width="50" height="50" class="img-thumbnail">
                                @else
                                    <span class="badge bg-secondary">No Image</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>
                                @if ($product->category)
                                    <span class="badge bg-info">{{ $product->category->name }}</span>
                                @else
                                    <span class="badge bg-secondary">Uncategorized</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($product->description, 50) }}</td>
                            <td>₱{{ number_format($product->cost_price, 2) }}</td>
                            <td><strong>₱{{ number_format($product->sell_price, 2) }}</strong></td>
                            <td>
                                @if ($product->stock->sum('quantity') > 0)
                                    <span class="badge bg-success">{{ $product->stock->sum('quantity') }}</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if (Auth::check() && Auth::user()->role === 'admin')
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete" 
                                                onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No products found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>

    <script>
        document.getElementById('productSearch').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#productsTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
@endsection
