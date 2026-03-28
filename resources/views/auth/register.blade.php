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
                    
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm" novalidate>
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="first_name" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           name="first_name" value="{{ old('first_name') }}" placeholder="John"
                                           autocomplete="given-name" autofocus>
                                    <small class="text-muted">2-255 characters, letters only</small>
                                    @error('first_name')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="last_name" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           name="last_name" value="{{ old('last_name') }}" placeholder="Doe"
                                           autocomplete="family-name">
                                    <small class="text-muted">2-255 characters, letters only</small>
                                    @error('last_name')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" placeholder="your@email.com"
                                   autocomplete="email">
                            <small class="text-muted">We'll never share your email</small>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" placeholder="••••••••"
                                       autocomplete="new-password"
                                       oninput="validatePassword()">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')" style="border-color: var(--border-color);">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="passwordStrength" class="mt-2"></div>
                            <small class="text-muted d-block mt-2">
                                ✓ At least 8 characters<br>
                                ✓ Uppercase letter (A-Z)<br>
                                ✓ Lowercase letter (a-z)<br>
                                ✓ Number (0-9)<br>
                                ✓ Special character (@$!%*?&)
                            </small>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password-confirm" class="form-label">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input id="password-confirm" type="password" class="form-control" 
                                       name="password_confirmation" placeholder="••••••••"
                                       autocomplete="new-password"
                                       oninput="validatePassword()">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm" onclick="togglePasswordVisibility('password-confirm', 'togglePasswordConfirm')" style="border-color: var(--border-color);">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="passwordMatch" class="mt-2"></div>
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

<script>
    // Registration form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registerForm');
        
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    function validatePassword() {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password-confirm').value;
        const strength = document.getElementById('passwordStrength');
        const match = document.getElementById('passwordMatch');

        let checks = {
            length: password.length >= 8,
            upper: /[A-Z]/.test(password),
            lower: /[a-z]/.test(password),
            number: /\d/.test(password),
            special: /[@$!%*?&]/.test(password)
        };

        // Password strength indicator
        if (password.length > 0) {
            let score = Object.values(checks).filter(Boolean).length;
            let strengthText = '';
            let strengthClass = '';

            if (score <= 2) {
                strengthText = '<small class="text-danger"><i class="fas fa-times-circle"></i> Weak</small>';
                strengthClass = 'text-danger';
            } else if (score === 3 || score === 4) {
                strengthText = '<small class="text-warning"><i class="fas fa-exclamation-circle"></i> Medium</small>';
                strengthClass = 'text-warning';
            } else {
                strengthText = '<small class="text-success"><i class="fas fa-check-circle"></i> Strong</small>';
                strengthClass = 'text-success';
            }
            strength.innerHTML = strengthText;
        } else {
            strength.innerHTML = '';
        }

        // Password match indicator
        if (confirm.length > 0) {
            if (password === confirm) {
                match.innerHTML = '<small class="text-success"><i class="fas fa-check-circle"></i> Passwords match</small>';
            } else {
                match.innerHTML = '<small class="text-danger"><i class="fas fa-times-circle"></i> Passwords do not match</small>';
            }
        } else {
            match.innerHTML = '';
        }
    }

    function previewPhoto(event) {
        const preview = document.getElementById('photoPreview');
        const file = event.target.files[0];
        
        if (file) {
            // Validate file size
            if (file.size > 2048 * 1024) {
                alert('Photo cannot exceed 2MB');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    if (this.width < 100 || this.height < 100) {
                        alert('Photo must be at least 100x100 pixels');
                        event.target.value = '';
                        preview.innerHTML = '';
                        return;
                    }
                    preview.innerHTML = `<img src="${e.target.result}" style="max-width: 150px; max-height: 150px; border-radius: 8px;" class="img-thumbnail">`;
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

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
