@extends('layouts.base')

@section('content')
    <div class="container-fluid mt-4">
        @include('layouts.flash-messages')

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="fas fa-info-circle"></i> Product Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @if ($product->img_path)
                                    <img src="{{ Storage::url($product->img_path) }}" alt="{{ $product->name }}" 
                                         class="img-fluid img-thumbnail">
                                @else
                                    <div class="alert alert-secondary">No image available</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Product ID:</th>
                                        <td>#{{ $product->product_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Product Name:</th>
                                        <td><h5>{{ $product->name }}</h5></td>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <td>
                                            @if ($product->category)
                                                <span class="badge bg-info">{{ $product->category->name }}</span>
                                            @else
                                                <span class="badge bg-secondary">Uncategorized</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Cost Price:</th>
                                        <td>₱{{ number_format($product->cost_price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sell Price:</th>
                                        <td><strong>₱{{ number_format($product->sell_price, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Stock Level:</th>
                                        <td>
                                            @if ($product->stock->sum('quantity') > 0)
                                                <span class="badge bg-success">{{ $product->stock->sum('quantity') }} units</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $product->created_at->format('M d, Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Description</h6>
                        <p>{{ $product->description }}</p>

                        @if ($product->images->count() > 0)
                            <hr>
                            <h6>Additional Images</h6>
                            <div class="row">
                                @foreach ($product->images as $image)
                                    <div class="col-md-3 mb-3">
                                        <img src="{{ Storage::url($image->img_path) }}" alt="Product image" 
                                             class="img-fluid img-thumbnail">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($product->reviews->count() > 0)
                            <hr>
                            <h6>Customer Reviews</h6>
                            @foreach ($product->reviews as $review)
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                                            <div>
                                                @for ($i = 0; $i < $review->rating; $i++)
                                                    <i class="fas fa-star" style="color: gold;"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="mb-0 mt-2">{{ $review->comment }}</p>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('cart.store') }}" class="btn btn-success btn-block mb-2 w-100">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-primary w-100">
                            <i class="fas fa-list"></i> View All Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
