<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid px-3 px-lg-4">
        <!-- Brand -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-campfire me-2"></i> EverPeak Camp Co.
        </a>

        <!-- Toggler Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="d-flex align-items-center gap-3 flex-wrap ms-auto">
                <!-- Search Form -->
                <form action="{{ route('shop.show') }}" class="d-flex me-lg-3 search-form" method="GET">
                    <input class="form-control form-control-sm me-2" type="search" 
                           placeholder="Search gear..." aria-label="Search" name="search" 
                           style="width: 180px; border-radius: 25px;">
                    <button class="btn btn-sm btn-warning rounded-pill" type="submit" title="Search">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <!-- Cart Button -->
                @if (!Auth::check() || Auth::user()->role !== 'admin')
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-light btn-sm position-relative rounded-pill" 
                       title="Shopping Cart">
                        <i class="fas fa-shopping-cart me-1"></i> Cart
                        @php
                            $cartCount = Auth::check() ? \App\Models\CartItem::where('user_id', Auth::id())->count() : 0;
                        @endphp
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" 
                                  style="background-color: var(--danger); font-size: 0.65rem; padding: 0.35rem 0.5rem;">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                @endif

                <!-- Account Dropdown -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ Auth::check() ? \Illuminate\Support\Str::limit(Auth::user()->name, 15) : 'Account' }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end rounded-3" style="border: 1px solid var(--border-color);" aria-labelledby="navbarDropdown">
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <h6 class="dropdown-header fw-bold" style="background: linear-gradient(135deg, var(--primary-green-light) 0%, var(--accent-green) 100%); color: white;">
                                    <i class="fas fa-shield-alt me-2"></i>Admin Panel
                                </h6>
                                <a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-chart-line me-2" style="color: var(--primary-green-light);"></i> Dashboard</a>
                                <a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users me-2" style="color: var(--info);"></i> Manage Users</a>
                                <a class="dropdown-item" href="{{ route('products.index') }}"><i class="fas fa-box me-2" style="color: var(--accent-green);"></i> Manage Products</a>
                                <a class="dropdown-item" href="{{ route('categories.index') }}"><i class="fas fa-folder me-2" style="color: var(--terracotta);"></i> Manage Categories</a>
                                <a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-receipt me-2" style="color: var(--danger);"></i> Manage Orders</a>
                                <a class="dropdown-item" href="{{ route('reviews.index') }}"><i class="fas fa-star me-2" style="color: #FFD700;"></i> Manage Reviews</a>
                                <hr class="dropdown-divider" style="margin: 0.5rem 0;">
                            @else
                                <a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-user me-2" style="color: var(--primary-green-light);"></i> My Profile</a>
                                <a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-receipt me-2" style="color: var(--accent-green);"></i> My Orders</a>
                                <hr class="dropdown-divider" style="margin: 0.5rem 0;">
                            @endif
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="dropdown-item" style="color: var(--danger); font-weight: 600;">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        @else
                            <a class="dropdown-item" href="{{ route('register') }}"><i class="fas fa-user-plus me-2" style="color: var(--accent-green);"></i> Register</a>
                            <a class="dropdown-item fw-bold" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2" style="color: var(--primary-green-light);"></i> Login</a>
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
        <div class="alert alert-success alert-dismissible fade show animate-slide-in-up" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show animate-slide-in-up" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif
