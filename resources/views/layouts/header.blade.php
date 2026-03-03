<nav class="navbar navbar-expand-lg navbar-dark shadow-lg" style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%);">
    <div class="container-fluid px-3 px-lg-4">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('home') }}" style="font-size: 1.3rem;">
            <i class="fas fa-mountain text-warning me-2"></i> EverPeak Camp Co.
        </a>

        <!-- Toggler Button -->
        <button class="navbar-toggler border-light" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto me-3">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <!-- Search Form -->
                <form action="{{ route('shop.show') }}" class="d-flex me-lg-3" method="GET">
                    <input class="form-control form-control-sm me-2 rounded-pill" type="search" 
                           placeholder="Search gear..." aria-label="Search" name="search" 
                           style="width: 180px; background-color: rgba(255, 255, 255, 0.95); border: none;">
                    <button class="btn btn-sm btn-warning rounded-pill" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <!-- Cart Button -->
                @if (!Auth::check() || Auth::user()->role !== 'admin')
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-light btn-sm position-relative rounded-pill" 
                       style="border-width: 2px; transition: all 0.3s ease;">
                        <i class="fas fa-shopping-cart me-1"></i> Cart
                        @php
                            $cartCount = Auth::check() ? \App\Models\CartItem::where('user_id', Auth::id())->count() : 0;
                        @endphp
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                  style="font-size: 0.7rem; padding: 0.4rem 0.5rem;">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                @endif

                <!-- Account Dropdown -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light fw-bold" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 0.95rem;">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ Auth::check() ? Auth::user()->name : 'Account' }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end rounded-3" style="border: 1px solid #ddd; min-width: 220px;" aria-labelledby="navbarDropdown">
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <h6 class="dropdown-header fw-bold" style="background-color: #f8f9fa; border-bottom: 2px solid #1a472a;">
                                    <i class="fas fa-shield-alt text-warning me-2"></i>Admin Panel
                                </h6>
                                <a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-chart-line text-primary me-2"></i> Dashboard</a>
                                <a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users text-info me-2"></i> Manage Users</a>
                                <a class="dropdown-item" href="{{ route('products.index') }}"><i class="fas fa-box text-success me-2"></i> Manage Products</a>
                                <a class="dropdown-item" href="{{ route('categories.index') }}"><i class="fas fa-folder text-warning me-2"></i> Manage Categories</a>
                                <a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-receipt text-danger me-2"></i> Manage Orders</a>
                                <hr class="dropdown-divider" style="margin: 0.5rem 0;">
                            @else
                                <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}"><i class="fas fa-user text-primary me-2"></i> My Profile</a>
                                <a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-receipt text-success me-2"></i> My Orders</a>
                                <a class="dropdown-item" href="{{ route('cart.index') }}"><i class="fas fa-shopping-cart text-info me-2"></i> Shopping Cart</a>
                                <hr class="dropdown-divider" style="margin: 0.5rem 0;">
                            @endif
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-bold">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        @else
                            <a class="dropdown-item" href="{{ route('register') }}"><i class="fas fa-user-plus text-success me-2"></i> Register</a>
                            <a class="dropdown-item fw-bold" href="{{ route('login') }}"><i class="fas fa-sign-in-alt text-primary me-2"></i> Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- flash messages shown on every page that includes header --}}
@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<style>
    .navbar {
        transition: all 0.3s ease;
    }
    
    .navbar-brand:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        border-color: #ffc107;
    }
    
    .btn-outline-light:hover {
        background-color: #ffc107;
        color: #1a472a !important;
        border-color: #ffc107;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .dropdown-item:hover {
        background-color: #f0f6ff;
        color: #1a472a;
    }
</style>
