@extends('layouts.base')

@section('content')
    <div class="container mt-4">
        @include('layouts.flash-messages')

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Product/Service</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $product->name) }}" 
                                       minlength="3" maxlength="255" pattern="^[a-zA-Z0-9\s\-&\/.,()]+$"
                                       title="Name can contain letters, numbers, and these symbols: - & / . , ( )" required>
                                <small class="text-muted">3-255 characters, alphanumeric with allowed symbols</small>
                                @error('name')
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span> 
                                    <span id="charCount" class="text-muted float-end"><small>0/5000</small></span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="5" minlength="10" maxlength="5000"
                                          placeholder="Detailed description (10-5000 characters)" required
                                          oninput="updateCharCount()">{{ old('description', $product->description) }}</textarea>
                                <small class="text-muted">10-5000 characters required</small>
                                @error('description')
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cost_price" class="form-label">Cost Price (₱) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('cost_price') is-invalid @enderror" 
                                               id="cost_price" name="cost_price" step="0.01" min="0" max="999999.99"
                                               value="{{ old('cost_price', $product->cost_price) }}" 
                                               placeholder="0.00" required oninput="validatePrices()">
                                        <small class="text-muted">Numeric, 0-999999.99</small>
                                        @error('cost_price')
                                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sell_price" class="form-label">Sell Price (₱) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('sell_price') is-invalid @enderror" 
                                               id="sell_price" name="sell_price" step="0.01" min="0" max="999999.99"
                                               value="{{ old('sell_price', $product->sell_price) }}" 
                                               placeholder="0.00" required oninput="validatePrices()">
                                        <small class="text-muted">Must be ≥ Cost Price</small>
                                        <div id="priceWarning" class="mt-2"></div>
                                        @error('sell_price')
                                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
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
                                                    {{ old('category_id', $product->category_id) == $category->category_id ? 'selected' : '' }}>
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
                                <label for="stocks" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control @error('stocks') is-invalid @enderror" 
                                       id="stocks" name="stocks" value="{{ old('stocks', $product->stock->first()->quantity ?? 0) }}" min="0" step="1" required>
                                <small class="form-text text-muted">Set the product inventory quantity</small>
                                @error('stocks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Main Product Image -->
                            <div class="mb-3">
                                <label for="img_path" class="form-label">Main Product Image</label>
                                @if ($product->img_path)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($product->img_path) }}" alt="{{ $product->name }}" 
                                             class="img-thumbnail" width="200">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('img_path') is-invalid @enderror" 
                                       id="img_path" name="img_path" accept="image/*" onchange="previewMainImage(event)">
                                <small class="form-text text-muted">JPG, PNG, GIF (Max 2MB, min 100x100px) - Leave blank to keep current</small>
                                <div id="mainImagePreview" class="mt-2"></div>
                                @error('img_path')
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gallery Photos Management -->
                            <div class="mb-3">
                                <label class="form-label">Photo Gallery</label>
                                
                                <!-- Existing Photos -->
                                @if(isset($images) && $images->count() > 0)
                                    <div class="mb-3">
                                        <h6>Existing Photos:</h6>
                                        <div class="row" id="existingPhotos">
                                            @foreach($images as $image)
                                                <div class="col-md-3 mb-2" id="photo-{{ $image->image_id }}">
                                                    <div class="position-relative">
                                                        <img src="{{ Storage::url($image->img_path) }}" class="img-thumbnail" style="width: 100%; height: auto;">
                                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                                                onclick="deleteProductImage({{ $image->image_id }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Add More Photos -->
                                <div class="mb-3">
                                    <label for="images" class="form-label">Add More Photos to Gallery</label>
                                    <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                           id="images" name="images[]" accept="image/*" multiple onchange="previewGalleryImages(event)">
                                    <small class="form-text text-muted">JPG, PNG, GIF (Max 2MB each, min 100x100px) - Multiple uploads allowed</small>
                                    <div id="galleryPreview" class="mt-2 row"></div>
                                    @error('images.*')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Update Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update character counter for description
        function updateCharCount() {
            const desc = document.getElementById('description');
            const counter = document.getElementById('charCount');
            counter.innerHTML = `<small>${desc.value.length}/5000</small>`;
        }

        // Validate prices
        function validatePrices() {
            const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
            const sellPrice = parseFloat(document.getElementById('sell_price').value) || 0;
            const warning = document.getElementById('priceWarning');

            if (sellPrice > 0 && costPrice > 0 && sellPrice < costPrice) {
                warning.innerHTML = '<small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Sell price is less than cost price!</small>';
            } else if (sellPrice > 0 && costPrice > 0) {
                warning.innerHTML = '<small class="text-success"><i class="fas fa-check-circle me-1"></i>Valid price configuration</small>';
            } else {
                warning.innerHTML = '';
            }
        }

        // Preview main image with validation
        function previewMainImage(event) {
            const preview = document.getElementById('mainImagePreview');
            const file = event.target.files[0];
            
            if (file) {
                // Validate file size
                if (file.size > 2048 * 1024) {
                    alert('Image cannot exceed 2MB');
                    event.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        if (this.width < 100 || this.height < 100) {
                            alert('Image must be at least 100x100 pixels');
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

        // Preview gallery images with validation
        function previewGalleryImages(event) {
            const preview = document.getElementById('galleryPreview');
            preview.innerHTML = '';
            
            let validCount = 0;
            Array.from(event.target.files).forEach((file, index) => {
                // Validate file size
                if (file.size > 2048 * 1024) {
                    alert(`Image ${index + 1} exceeds 2MB limit - skipped`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        if (this.width < 100 || this.height < 100) {
                            alert(`Image ${index + 1} is smaller than 100x100 pixels - skipped`);
                            return;
                        }
                        validCount++;
                        const col = document.createElement('div');
                        col.className = 'col-md-3 mb-2';
                        col.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; height: auto;" class="img-thumbnail" title="Image ${validCount}">`;
                        preview.appendChild(col);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        function deleteProductImage(imageId) {
            if (!confirm('Are you sure you want to delete this image?')) {
                return;
            }

            fetch(`/products-image/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`photo-${imageId}`).remove();
                    alert('Image deleted successfully');
                } else {
                    alert('Error deleting image');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting image');
            });
        }

        // Initialize character counter on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCharCount();
            validatePrices();
        });
    </script>
@endsection
