@extends('layouts.base')

@section('title', 'Shop All Products - Everpeak Camp Co')

@section('content')
<!-- Shop Header -->
<div class="bg-dark text-white py-5" style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%);">
    <div class="container py-4 text-center">
        <h1 class="display-4 fw-bold mb-2 text-white">Shop All Products
        </h1>
        <p class="lead text-light">Browse our complete collection of outdoor & camping gear</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4">
        <!-- LEFT COLUMN: Search & Categories -->
        <div class="col-lg-3">
            <div class="card rounded-3 shadow-sm" style="border: none; position: sticky; top: 20px;">
                <div style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; padding: 1.5rem; border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 fw-bold text-white">
                        <i class="fas fa-search me-2"></i>Search & Category
                    </h5>
                </div>
                <div class="card-body p-3">
                    <!-- Search Bar (Independent Form) -->
                    <div class="mb-4">
                        <form id="searchForm" onsubmit="submitSearchForm(event)">
                            <div class="input-group">
                                <input type="text" id="searchInput" name="search" class="form-control rounded-start" 
                                       placeholder="Search products..." value="{{ $search }}"
                                       style="border-color: #1a472a;">
                                <button class="btn" type="submit" style="background-color: #1a472a; color: white; border-color: #1a472a;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Categories with Checkboxes (Independent Form) -->
                    <div>
                        <h6 class="fw-bold mb-3" style="color: #1a472a;">
                            <i class="fas fa-folder me-2"></i>Categories
                        </h6>
                        <form id="categoryForm">
                            @foreach($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input category-checkbox" type="checkbox" name="category[]" 
                                           id="category{{ $category->category_id }}" 
                                           value="{{ $category->category_id }}"
                                           @checked(in_array($category->category_id, $selectedCategories))
                                           onchange="submitCategoryForm()">
                                    <label class="form-check-label" for="category{{ $category->category_id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </form>
                    </div>

                    <!-- Clear Filters Button -->
                    <hr class="my-3">
                    <a href="{{ route('shop.show') }}" class="btn btn-outline-danger btn-sm w-100 rounded-pill">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                </div>
            </div>
        </div>

            <!-- MIDDLE COLUMN: Products -->
            <div class="col-lg-6">


            @if ($products->count() > 0)
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 fw-bold" style="color: #1a472a;">
                        <i class="fas fa-box me-2"></i>{{ $products->total() }} Products Found
                    </h5>
                </div>

                <div class="row g-4 mb-5">
                    @foreach ($products as $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm rounded-3" style="transition: transform 0.3s ease; border: none; overflow: hidden;">
                                <!-- Product Image -->
                                <div style="height: 220px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    @if($product->img_path)
                                        <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->name }}" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    @endif
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-subtitle text-muted mb-2" style="color: #1a472a;">
                                        <i class="fas fa-tag me-1"></i>{{ $product->category->name ?? 'Uncategorized' }}
                                    </h6>
                                    <h5 class="card-title fw-bold">{{ \Illuminate\Support\Str::limit($product->name, 40) }}</h5>
                                    <p class="card-text text-muted small flex-grow-1">{{ \Illuminate\Support\Str::limit($product->description, 70) }}</p>

                                    <!-- Stock Status -->
                                    @php
                                        $stockQuantity = $product->stock->sum('quantity');
                                    @endphp
                                    <div class="mb-3">
                                        @if($stockQuantity > 0)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>{{ $stockQuantity }} in stock
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Out of Stock
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Price -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <span class="h5 text-success fw-bold">₱{{ number_format($product->sell_price, 2) }}</span>
                                            @if($product->cost_price < $product->sell_price)
                                                <span class="text-muted text-decoration-line-through small d-block" style="font-size: 0.85rem;">
                                                    ₱{{ number_format($product->cost_price, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('products.show', $product->product_id) }}" class="btn btn-outline-primary rounded-pill btn-sm">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                        @if($stockQuantity > 0)
                                            <form action="{{ route('cart.store') }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-success w-100 rounded-pill btn-sm">
                                                    <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-secondary w-100 rounded-pill btn-sm" disabled>
                                                <i class="fas fa-ban me-1"></i> Out of Stock
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- No Products Message -->
                <div class="text-center py-5">
                    <i class="fas fa-search" style="font-size: 3rem; color: #1a472a; opacity: 0.3; display: block; margin-bottom: 1rem;"></i>
                    <h4 class="fw-bold mb-3" style="color: #1a472a;">No Products Found</h4>
                    <p class="text-muted mb-4">
                        @if(!empty($search))
                            We couldn't find any products matching "{{ $search }}". <br>
                        @elseif(!empty($selectedCategories) || !empty($minPrice) || !empty($maxPrice))
                            No products match the selected filters. <br>
                        @else
                            No products available at the moment. <br>
                        @endif
                        Try adjusting your filters or search terms.
                    </p>
                    <a href="{{ route('shop.show') }}" class="btn rounded-pill fw-bold" 
                       style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; padding: 0.75rem 2rem;">
                        <i class="fas fa-redo me-2"></i> Clear Filters
                    </a>
                </div>
            @endif
            </div>

            <!-- RIGHT COLUMN: Price Range & Sort By -->
            <div class="col-lg-3">
                <div class="card rounded-3 shadow-sm" style="border: none; position: sticky; top: 20px;">
                    <div style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; padding: 1.5rem; border-radius: 12px 12px 0 0;">
                        <h5 class="mb-0 fw-bold text-white">
                            <i class="fas fa-sliders me-2"></i>Price & Sort
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <!-- Price Range Form (Independent) -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3" style="color: #1a472a;">
                                <i class="fas fa-tag me-2"></i>Price Range
                            </h6>
                            
                            <div class="mb-3">
                                <label for="minPrice" class="form-label text-muted" style="font-size: 0.9rem;">Min Price (₱)</label>
                                <input type="number" id="minPrice" class="form-control form-control-sm" 
                                       placeholder="{{ number_format($priceStats->min_price ?? 0, 2, '.', '') }}" min="0" step="0.01" value="{{ $minPrice }}"
                                       style="border-color: #1a472a;" onblur="submitPriceFilter()">
                            </div>

                            <div class="mb-3">
                                <label for="maxPrice" class="form-label text-muted" style="font-size: 0.9rem;">Max Price (₱)</label>
                                <input type="number" id="maxPrice" class="form-control form-control-sm" 
                                       placeholder="{{ number_format($priceStats->max_price ?? 0, 2, '.', '') }}" min="0" step="0.01" value="{{ $maxPrice }}"
                                       style="border-color: #1a472a;" onblur="submitPriceFilter()">
                            </div>
                        </div>

                        <!-- Sorting Form (Independent) -->
                        <hr>
                        <div class="mt-3">
                            <h6 class="fw-bold mb-3" style="color: #1a472a;">
                                <i class="fas fa-sort me-2"></i>Sort By
                            </h6>
                            
                            <div class="mb-2">
                                <select id="sortBy" class="form-select form-select-sm" style="border-color: #1a472a;" onchange="submitSortForm()">
                                    <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Product Name</option>
                                    <option value="sell_price" {{ $sortBy == 'sell_price' ? 'selected' : '' }}>Price</option>
                                    <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Newest</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <select id="sortOrder" class="form-select form-select-sm" style="border-color: #1a472a;" onchange="submitSortForm()">
                                    <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Ascending</option>
                                    <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Descending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }
    
    .form-check-input:checked {
        background-color: #1a472a;
        border-color: #1a472a;
    }
    
    .form-check-input:focus {
        border-color: #1a472a;
        box-shadow: 0 0 0 0.25rem rgba(26, 71, 42, 0.25);
    }
</style>

<script>
    // Build current filter URL with all active filters
    function buildFilterUrl(params = {}) {
        const url = new URL('{{ route("shop.show") }}', window.location.origin);
        
        // Preserve existing filters
        const search = document.getElementById('searchInput')?.value || '{{ $search }}' || '';
        if (search) url.searchParams.append('search', search);
        
        // Categories
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox:checked');
        categoryCheckboxes.forEach(checkbox => {
            url.searchParams.append('category[]', checkbox.value);
        });
        
        // Price
        const minPrice = document.getElementById('minPrice')?.value || '{{ $minPrice }}' || '';
        if (minPrice) url.searchParams.append('min_price', minPrice);
        
        const maxPrice = document.getElementById('maxPrice')?.value || '{{ $maxPrice }}' || '';
        if (maxPrice) url.searchParams.append('max_price', maxPrice);
        
        // Sort
        const sortBy = document.getElementById('sortBy')?.value || '{{ $sortBy }}' || 'name';
        url.searchParams.append('sort_by', sortBy);
        
        const sortOrder = document.getElementById('sortOrder')?.value || '{{ $sortOrder }}' || 'asc';
        url.searchParams.append('sort_order', sortOrder);
        
        // Override with any new params
        Object.keys(params).forEach(key => {
            if (params[key] !== '' && params[key] !== null && params[key] !== undefined) {
                url.searchParams.set(key, params[key]);
            }
        });
        
        return url.toString();
    }

    // Search form submission
    function submitSearchForm(event) {
        event.preventDefault();
        const searchValue = document.getElementById('searchInput').value;
        window.location.href = buildFilterUrl({ search: searchValue });
    }

    // Category form submission
    function submitCategoryForm() {
        window.location.href = buildFilterUrl();
    }

    // Price filter submission
    function submitPriceFilter() {
        const minPrice = document.getElementById('minPrice').value;
        const maxPrice = document.getElementById('maxPrice').value;
        window.location.href = buildFilterUrl({ 
            min_price: minPrice,
            max_price: maxPrice
        });
    }

    // Sort form submission
    function submitSortForm() {
        const sortBy = document.getElementById('sortBy').value;
        const sortOrder = document.getElementById('sortOrder').value;
        window.location.href = buildFilterUrl({ 
            sort_by: sortBy,
            sort_order: sortOrder
        });
    }
</script>
@endsection
