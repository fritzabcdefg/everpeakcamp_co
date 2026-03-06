@extends('layouts.base')

@section('content')
    <div class="container-fluid mt-4">
        @include('layouts.flash-messages')

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gear Categories</h2>
            @if (Auth::check() && Auth::user()->role === 'admin')
                <a class="btn btn-primary" href="{{ route('categories.create') }}" role="button">
                    <i class="fas fa-plus"></i> Add Category
                </a>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>#{{ $category->category_id }}</td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>{{ Str::limit($category->description, 50) }}</td>
                            <td>
                                <span class="badge bg-info">{{ $category->products->count() }}</span>
                            </td>
                            <td>
                                <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if (Auth::check() && Auth::user()->role === 'admin')
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No categories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
