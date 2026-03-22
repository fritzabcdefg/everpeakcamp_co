@extends('layouts.base')

@section('content')
    <div class="container mt-4">
        @include('layouts.flash-messages')

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Order #{{ $order->order_id }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Customer</label>
                                <select class="form-control @error('customer_id') is-invalid @enderror" 
                                        id="customer_id" name="customer_id">
                                    <option value="">-- Select Customer --</option>
                                    @foreach (\App\Models\Customer::all() as $customer)
                                        <option value="{{ $customer->customer_id }}" 
                                                {{ old('customer_id', $order->customer_id) == $customer->customer_id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="shipping_fee" class="form-label">Shipping Fee $ *</label>
                                <input type="number" class="form-control @error('shipping_fee') is-invalid @enderror" 
                                       id="shipping_fee" name="shipping_fee" step="0.01" min="0" 
                                       value="{{ old('shipping_fee', $order->shipping_fee ?? 0) }}" required>
                                @error('shipping_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="">-- Select Status --</option>
                                    <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Update Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
