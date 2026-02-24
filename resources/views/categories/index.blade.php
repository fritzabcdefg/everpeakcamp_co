@extends('layouts.base')

@section('body')
    <div class="container mt-4">
        @include('layouts.flash-messages')

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gear Categories</h2>
            @if (Auth::check() && Auth::user()->role === 'admin')
                <a class="btn btn-primary" href="{{ route('categories.create') }}" role="button">
                    <i class="fas fa-plus"></i> Add Category
                </a>
            @endif
        </div>

        <div class="row">
            @forelse ($categories as $category)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($category->description, 80) }}</p>
                            <div class="badge bg-info mb-3">{{ $category->products->count() }} products</div>
                        </div>
                        <div class="card-footer bg-light">
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if (Auth::check() && Auth::user()->role === 'admin')
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> No categories found. <a href="{{ route('categories.create') }}">Create one now</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
