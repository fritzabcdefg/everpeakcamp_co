@extends('layouts.base')

@section('content')
    <div class="container mt-4">
        @include('layouts.flash-messages')

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Add New Product/Service</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cost_price" class="form-label">Cost Price *</label>
                                        <input type="number" class="form-control @error('cost_price') is-invalid @enderror" 
                                               id="cost_price" name="cost_price" step="0.01" min="0" 
                                               value="{{ old('cost_price') }}" required>
                                        @error('cost_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sell_price" class="form-label">Sell Price *</label>
                                        <input type="number" class="form-control @error('sell_price') is-invalid @enderror" 
                                               id="sell_price" name="sell_price" step="0.01" min="0" 
                                               value="{{ old('sell_price') }}" required>
                                        @error('sell_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
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
        function previewMainImage(event) {
            const preview = document.getElementById('mainImagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">`;
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
                                               id="sell_price" name="sell_price" step="0.01" min="0" 
                                               value="{{ old('sell_price') }}" required>
                                        @error('sell_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stocks" class="form-label">Stocks</label>
                                        <input type="number" class="form-control @error('stocks') is-invalid @enderror" 
                                               id="stocks" name="stocks" step="1" min="0" 
                                               value="{{ old('stocks') }}" required>
                                        @error('stocks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-control @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id">
                                    <option value="">-- Select Category --</option>
                                    @foreach (\App\Models\Category::all() as $category)
                                        <option value="{{ $category->category_id }}" 
                                                {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="img_path" class="form-label">Product Image</label>
                                <input type="file" class="form-control @error('img_path') is-invalid @enderror" 
                                       id="img_path" name="img_path" accept="image/*">
                                <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF (Max 2MB)</small>
                                @error('img_path')
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
@endsection
