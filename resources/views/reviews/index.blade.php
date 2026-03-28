@extends('layouts.base')

@section('title', 'Manage Reviews - EverPeak Camp')

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 style="color: var(--primary-green-light); font-weight: 600;">
                <i class="fas fa-star me-2"></i>Manage Customer Reviews
            </h2>
        </div>
    </div>

    <div class="card shadow-nature rounded-nature">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="reviews-table" class="table table-striped table-hover">
                    <thead style="background-color: rgba(76, 175, 80, 0.1);">
                        <tr>
                            <th>Product ID</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script>
    $(document).ready(function() {
        $('#reviews-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('reviews.datatable') }}",
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
                { data: 'product_id' },
                { data: 'product' },
                { data: 'customer' },
                { data: 'rating', render: function(data) { return data; }, orderable: false },
                { data: 'comment' },
                { data: 'date' },
                { data: 'actions', render: function(data) { return data; }, orderable: false, searchable: false }
            ],
            pageLength: 10,
            lengthMenu: [10, 15, 25, 50, 100],
            order: [[4, 'desc']],
        });
    });
</script>
@endpush
@endsection
