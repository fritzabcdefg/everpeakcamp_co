@extends('layouts.base')

@section('content')
    <div class="container mt-4">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0" style="color: var(--cream);"><i class="fas fa-upload"></i> Import Products from Excel</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <h6>Import Instructions:</h6>
                            <ul class="mb-0">
                                <li>Your Excel file should have the following columns:</li>
                                <li><strong>name</strong> or <strong>product_name</strong> - Product name (Required)</li>
                                <li><strong>description</strong> - Product description</li>
                                <li><strong>cost_price</strong> or <strong>cost</strong> - Cost price (Required, numeric)</li>
                                <li><strong>sell_price</strong> or <strong>price</strong> - Selling price (Required, numeric)</li>
                                <li><strong>category_id</strong> - Category ID (Optional)</li>
                                <li>The first row should contain column headers</li>
                            </ul>
                        </div>

                        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="file" class="form-label">Select Excel File *</label>
                                <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                       id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                <small class="form-text text-muted">Supported formats: XLSX, XLS, CSV</small>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    Please ensure your data is valid before importing. Invalid rows will not be imported.
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-file-upload"></i> Import Products
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="table-responsive">
                            <h6>Sample Excel Format:</h6>
                            <table class="table table-sm table-borderless">
                                <thead class="table-light">
                                    <tr>
                                        <th>name</th>
                                        <th>description</th>
                                        <th>cost_price</th>
                                        <th>sell_price</th>
                                        <th>category_id</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Camping Tent</td>
                                        <td>4-person tent</td>
                                        <td>150.00</td>
                                        <td>299.99</td>
                                        <td>1</td>
                                    </tr>
                                    <tr>
                                        <td>Sleeping Bag</td>
                                        <td>Lightweight sleeping bag</td>
                                        <td>50.00</td>
                                        <td>99.99</td>
                                        <td>1</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="mt-2">
                                <a href="{{ asset('files/sample-products.xlsx') }}" class="btn btn-sm btn-outline-primary" download>
                                    <i class="fas fa-download"></i> Download Sample Excel File
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
