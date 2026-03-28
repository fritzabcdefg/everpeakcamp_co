@extends('layouts.base')

@section('content')
    <div class="container-fluid mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gear Categories</h2>
            @if (Auth::check() && Auth::user()->role === 'admin')
                <a class="btn btn-primary" href="{{ route('categories.create') }}" role="button">
                    <i class="fas fa-plus"></i> Add Category
                </a>
            @endif
        </div>

        <div class="table-responsive">
            <table id="categories-table" class="table table-striped table-hover">
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
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <script>
        $(document).ready(function() {
            $('#categories-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('categories.datatable') }}",
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTable AJAX Error:', error);
                        console.error('Status:', xhr.status);
                        console.error('Response:', xhr.responseText);
                        console.error('Thrown:', thrown);
                    }
                },
                columns: [
                    { data: 'id', render: function(data) { return '#' + data; } },
                    { data: 'name' },
                    { data: 'description', render: function(data) { return data ? data.substring(0, 50) + (data.length > 50 ? '...' : '') : '-'; } },
                    { data: 'product_count', render: function(data) { return data; }, orderable: false },
                    { data: 'actions', render: function(data) { return data; }, orderable: false, searchable: false }
                ],
                pageLength: 10,
                lengthMenu: [10, 15, 25, 50, 100],
                order: [[0, 'desc']]
            });
        });
    </script>
    @endpush
@endsection
