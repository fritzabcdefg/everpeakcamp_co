@extends('layouts.base')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">{{ $review ? 'Edit' : 'Write' }} Review for "{{ $product->name }}"</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ $review ? route('reviews.update', $review) : route('reviews.store') }}">
                            @csrf
                            @if ($review)
                                @method('PUT')
                            @endif

                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-select" required>
                                    <option value="">Choose</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('rating', $review->rating ?? '') == $i ? 'selected' : '' }}>{{ $i }} star(s)</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Comment (optional)</label>
                                <textarea name="comment" rows="4" class="form-control">{{ old('comment', $review->comment ?? '') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ $review ? 'Update' : 'Submit' }} Review</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
