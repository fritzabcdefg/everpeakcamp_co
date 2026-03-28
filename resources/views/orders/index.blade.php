@extends('layouts.base')

@section('content')
    <div class="container-fluid mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: var(--cream);">Orders</h2>
            @if (Auth::check() && Auth::user()->role === 'admin')
                <a class="btn btn-primary" href="{{ route('orders.create') }}" role="button">
                    <i class="fas fa-plus"></i> Create Order
                </a>
            @endif
        </div>

        <div class="table-responsive">
            <table id="orders-table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Items</th>
                        <th>Status</th>
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
            $('#orders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('orders.datatable') }}",
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
                    { data: 'order_id' },
                    { data: 'order_date' },
                    { data: 'customer_name' },
                    { data: 'total_amount' },
                    { data: 'item_count' },
                    { data: 'status', render: function(data) { return data; }, orderable: false },
                    { data: 'actions', render: function(data) { return data; }, orderable: false, searchable: false }
                ],
                pageLength: 15,
                lengthMenu: [10, 15, 25, 50, 100],
                order: [[1, 'desc']],
            });
        });
    </script>
    @endpush
@endsection
