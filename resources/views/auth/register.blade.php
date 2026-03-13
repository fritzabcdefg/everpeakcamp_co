@extends('layouts.base')

@section('title', 'Register - EverPeak Camp')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-nature rounded-nature">
                <div class="card-header" style="background: linear-gradient(135deg, var(--primary-green-light) 0%, var(--accent-green) 100%);">
                    <h4 class="mb-0 text-center" style="color: white; font-weight: 600;">
                        <i class="fas fa-user-plus me-2"></i>{{ __('Create Your Account') }}
                    </h4>
                </div>

                <div class="card-body p-4">
                    <p class="text-muted text-center mb-4">Join EverPeak Camp and start your outdoor adventure!</p>
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ __('Full Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" placeholder="John Doe" required autocomplete="name" autofocus>
                            @error('name')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" placeholder="your@email.com" required autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" placeholder="••••••••" required autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">Use at least 8 characters with mix of letters, numbers & symbols</small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control" 
                                   name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3" style="padding: 0.75rem;">
                            <i class="fas fa-user-plus me-2"></i>{{ __('Create Account') }}
                        </button>

                        <div class="text-center" style="border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                            <p style="color: var(--gray-text); margin-bottom: 0;">
                                {{ __('Already have an account?') }} 
                                <a href="{{ route('login') }}" style="color: var(--accent-green); font-weight: 600; text-decoration: none;">
                                    {{ __('Login Here') }}
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-info mt-4 rounded-nature" style="border-left: 4px solid var(--info); background-color: rgba(91, 192, 222, 0.1);">
                <i class="fas fa-leaf me-2" style="color: var(--primary-green-light);"></i>
                <small>By registering, you agree to our Terms & Conditions and Privacy Policy.</small>
            </div>
        </div>
    </div>
</div>
@endsection
