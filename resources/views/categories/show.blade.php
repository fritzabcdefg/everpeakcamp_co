@extends('layouts.base')

@section('body')
    <div class="container mt-4">
        @include('layouts.flash-messages')

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="fas fa-folder"></i> {{ $category->name }}</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Description:</strong></p>
                        <p>{{ $category->description ?? 'No description provided' }}</p>

                        <hr>

                        <h5>Products in this Category ({{ $category->products->count() }})</h5>
                        @if ($category->products->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product ID</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($category->products as $product)
                                            <tr>
                                                <td>#{{ $product->product_id }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>${{ number_format($product->sell_price, 2) }}</td>
                                                <td>
                                                    @if ($product->stock->sum('quantity') > 0)
                                                        <span class="badge bg-success">{{ $product->stock->sum('quantity') }}</span>
                                                    @else
                                                        <span class="badge bg-danger">Out of Stock</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">No products in this category yet.</div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
