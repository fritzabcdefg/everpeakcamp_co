@extends('layouts.base')

@section('content')
    <div class="container mt-4">

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Add New Product/Service</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" placeholder="Enter product name">
                                <small class="form-text text-muted">3-255 characters, letters, numbers, and special characters allowed</small>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4"
                                          placeholder="Enter detailed product description">{{ old('description') }}</textarea>
                                <div class="d-flex justify-content-between">
                                    <small class="form-text text-muted">10-5000 characters required</small>
                                    <small class="form-text text-muted"><span id="charCount">0</span>/5000</small>
                                </div>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cost_price" class="form-label">Cost Price <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" class="form-control @error('cost_price') is-invalid @enderror" 
                                                   id="cost_price" name="cost_price"
                                                   value="{{ old('cost_price') }}" placeholder="0.00"
                                                   oninput="validatePrices()">
                                        </div>
                                        <small class="form-text text-muted">Must be a positive number</small>
                                        @error('cost_price')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sell_price" class="form-label">Selling Price <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" class="form-control @error('sell_price') is-invalid @enderror" 
                                                   id="sell_price" name="sell_price"
                                                   value="{{ old('sell_price') }}" placeholder="0.00"
                                                   oninput="validatePrices()">
                                        </div>
                                        <small class="form-text text-muted">Must be ≥ cost price</small>
                                        <div id="priceWarning" class="text-warning mt-1" style="display: none;">
                                            <small><i class="fas fa-exclamation-triangle"></i> Selling price should be greater than cost price</small>
                                        </div>
                                        @error('sell_price')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-control @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id">
                                    <option value="">-- Select Category --</option>
                                    @if(isset($categories))
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->category_id }}" 
                                                    {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="stocks" class="form-label">Initial Stock</label>
                                <input type="number" class="form-control @error('stocks') is-invalid @enderror" 
                                       id="stocks" name="stocks" value="{{ old('stocks', 0) }}">
                                <small class="form-text text-muted">Enter starting inventory quantity</small>
                                @error('stocks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Main Product Image -->
                            <div class="mb-3">
                                <label for="img_path" class="form-label">Main Product Image</label>
                                <input type="file" class="form-control @error('img_path') is-invalid @enderror" 
                                       id="img_path" name="img_path" accept="image/*" onchange="previewMainImage(event)">
                                <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF (Max 2MB)</small>
                                <div id="mainImagePreview" class="mt-2"></div>
                                @error('img_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Additional Gallery Images -->
                            <div class="mb-3">
                                <label for="images" class="form-label">Additional Photos (Gallery)</label>
                                <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                       id="images" name="images[]" accept="image/*" multiple onchange="previewGalleryImages(event)">
                                <small class="form-text text-muted">You can upload multiple photos (Max 2MB each)</small>
                                <div id="galleryPreview" class="mt-2 row"></div>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Add Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Product form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('productForm');
            const descField = document.getElementById('description');
            const charCount = document.getElementById('charCount');

            // Character counter
            descField.addEventListener('input', function() {
                charCount.textContent = this.value.length;
                if (this.value.length < 10) {
                    this.classList.add('is-invalid');
                } else if (this.value.length <= 5000) {
                    this.classList.remove('is-invalid');
                }
            });

            // Form submit validation
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Price validation
        function validatePrices() {
            const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
            const sellPrice = parseFloat(document.getElementById('sell_price').value) || 0;
            const priceWarning = document.getElementById('priceWarning');

            if (sellPrice < costPrice && sellPrice > 0) {
                priceWarning.style.display = 'block';
            } else {
                priceWarning.style.display = 'none';
            }
        }

        function previewMainImage(event) {
            const preview = document.getElementById('mainImagePreview');
            const file = event.target.files[0];
            
            if (file) {
                // Validate file size
                if (file.size > 2048 * 1024) {
                    alert('Main image cannot exceed 2MB');
                    event.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        if (this.width < 100 || this.height < 100) {
                            alert('Main image must be at least 100x100 pixels');
                            event.target.value = '';
                            preview.innerHTML = '';
                            return;
                        }
                        preview.innerHTML = `<img src="${e.target.result}" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">`;
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function previewGalleryImages(event) {
            const preview = document.getElementById('galleryPreview');
            preview.innerHTML = '';
            
            Array.from(event.target.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-2';
                    col.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; height: auto;" class="img-thumbnail">`;
                    preview.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endsection
