@extends('layouts.base')

@section('content')
    <div class="container-fluid mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>User Management</h2>
            @if (Auth::check() && Auth::user()->role === 'admin')
                <a class="btn btn-primary" href="{{ route('users.create') }}" role="button">
                    <i class="fas fa-user-plus"></i> Add User
                </a>
            @endif
        </div>

        <div class="table-responsive">
            <table id="users-table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
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
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.datatable') }}",
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
                    { data: 'id' },
                    { data: 'photo', render: function(data) { return data; }, orderable: false },
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'phone' },
                    { data: 'role', render: function(data) { return data; }, orderable: false },
                    { data: 'status', render: function(data) { return data; }, orderable: false },
                    { data: 'created' },
                    { data: 'actions', render: function(data) { return data; }, orderable: false, searchable: false }
                ],
                pageLength: 15,
                lengthMenu: [10, 15, 25, 50, 100],
                order: [[7, 'desc']],
            });
        });
    </script>
    @endpush
@endsection
