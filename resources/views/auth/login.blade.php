@extends('layouts.auth')

@section('title', 'Login - EverPeak Camp')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-nature rounded-nature">
                <div class="card-header" style="background: linear-gradient(135deg, var(--primary-green-light) 0%, var(--accent-green) 100%); padding: 1rem;">
                    <a href="{{ route('home') }}" class="btn btn-light btn-sm rounded-pill" style="font-weight: 600;">
                        <i class="fas fa-arrow-left me-2"></i>Back to Home
                    </a>
                </div>

                <div class="card-body p-4">
                    <p class="text-muted text-center mb-4">Welcome back! Please login to your account</p>
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" placeholder="your@email.com" autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" placeholder="••••••••" autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')" style="border-color: var(--border-color);">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember" style="color: var(--gray-text);">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3" style="padding: 0.75rem;">
                            <i class="fas fa-sign-in-alt me-2"></i>{{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                            <div class="text-center mb-3">
                                <a href="{{ route('password.request') }}" style="color: var(--primary-green-light); text-decoration: none; transition: color 0.3s;">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        @endif

                        <div class="text-center mb-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#resendVerificationModal">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </div>

                        <div class="text-center" style="border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                            <p style="color: var(--gray-text); margin-bottom: 0;">
                                {{ __('Don\'t have an account?') }} 
                                <a href="{{ route('register') }}" style="color: var(--accent-green); font-weight: 600; text-decoration: none;">
                                    {{ __('Register Now') }}
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-info mt-4 rounded-nature" style="border-left: 4px solid var(--info); background-color: rgba(91, 192, 222, 0.1);">
                <i class="fas fa-leaf me-2" style="color: var(--primary-green-light);"></i>
                <small>For security, always use a strong password and keep your login credentials private.</small>
            </div>
        </div>
    </div>
</div>

<!-- Resend Verification Email Modal -->
<div class="modal fade" id="resendVerificationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-green-light) 0%, var(--accent-green) 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-envelope me-2"></i>{{ __('Resend Verification Email') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Enter your email address to receive a new verification link.</p>
                
                <form method="POST" action="{{ route('email.resend') }}">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label for="resend_email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="resend_email" type="email" class="form-control" 
                               name="email" placeholder="your@email.com" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane me-2"></i>{{ __('Resend Verification Email') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- flash messages are handled in header component --}}

<script>
    // Toggle password visibility
    function togglePasswordVisibility(inputId, buttonId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

@endsection
