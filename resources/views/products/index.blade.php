@extends('layouts.base')

@section('content')
    <div id="products" class="container-fluid mt-4">
        @include('layouts.flash-messages')
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Product & Service Management</h2>
            <div class="btn-group">
                @if (Auth::check() && Auth::user()->role === 'admin')
                    <a class="btn btn-primary" href="{{ route('products.create') }}" role="button">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                    <a class="btn btn-info" href="{{ route('products.importForm') }}" role="button">
                        <i class="fas fa-upload"></i> Import Excel
                    </a>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" id="productSearch" class="form-control" placeholder="Search products by name...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" id="includeTrashed">
                            <span class="form-check-label">Show Deleted</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="productsTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Cost Price</th>
                        <th>Sell Price</th>
                        <th>Stock</th>
                        <th>Photos</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Link DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            let table = $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('products.datatable') }}",
                    type: "GET",
                    data: function(d) {
                        d.include_trashed = $('#includeTrashed').is(':checked') ? 'true' : 'false';
                    }
                },
                columns: [
                    { data: 'product_id' },
                    { data: 'image', orderable: false, searchable: false },
                    { data: 'name' },
                    { data: 'category' },
                    { data: 'cost_price' },
                    { data: 'sell_price' },
                    { data: 'stock', orderable: false, searchable: false },
                    { data: 'photos', orderable: false, searchable: false },
                    { data: 'status', orderable: false, searchable: false },
                    { data: 'actions', orderable: false, searchable: false },
                ],
                pageLength: 15,
                lengthMenu: [[10, 15, 25, 50], [10, 15, 25, 50]]
            });

            // Search functionality
            $('#productSearch').on('keyup', function() {
                table.search($(this).val()).draw(false);
            });

            // Toggle trashed products
            $('#includeTrashed').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endsection
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
