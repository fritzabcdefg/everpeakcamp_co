<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid px-2 px-lg-2">
        <!-- Brand -->
        <a class="navbar-brand" href="{{ route('home') }}" title="Everpeak Camp Co - Back to Home">
            <img src="{{ asset('storage/Everpeak Camp Co Logo.svg') }}" alt="Everpeak Camp Co Logo" 
                 style="height: 80px; width: 80px; border-radius: 50%; object-fit: contain; cursor: pointer;">
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
                <!-- Cart Button -->
                @if (!Auth::check() || Auth::user()->role !== 'admin')
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-light position-relative rounded-pill" 
                       title="Shopping Cart" style="padding: 0.6rem 1.2rem; font-size: 1.1rem;">
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

                <!-- Profile Photo Dropdown (Only for authenticated users) -->
                @auth
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle p-0" href="#" id="profileDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="My Profile">
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->first_name }}" 
                                     class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid var(--accent-green);">
                            @else
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-green-light) 0%, var(--accent-green) 100%); color: white; font-weight: bold; font-size: 24px;">
                                    {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                                </div>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end rounded-3" style="border: 1px solid var(--border-color); min-width: 220px;" aria-labelledby="profileDropdown">
                            <div class="dropdown-header" style="background: linear-gradient(135deg, var(--primary-green-light) 0%, var(--accent-green) 100%); color: white; border-radius: 8px 8px 0 0;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    @if(Auth::user()->photo)
                                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->first_name }}" 
                                             class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                             style="width: 35px; height: 35px; background: rgba(255,255,255,0.3); color: white; font-weight: bold; font-size: 16px;">
                                            {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600;">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                        <div style="font-size: 12px; opacity: 0.9;">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                            </div>
                            <hr class="dropdown-divider" style="margin: 0.5rem 0;">
                            @if(Auth::user()->role === 'admin')
                                <h6 class="dropdown-subheader" style="color: var(--primary-green-dark); font-size: 12px; font-weight: 700; margin: 0.5rem 0 0.5rem 1rem;">ADMIN</h6>
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
                                @if(!Auth::user()->photo)
                                    <a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-camera me-2" style="color: var(--terracotta);"></i> Upload Photo</a>
                                @endif
                                <hr class="dropdown-divider" style="margin: 0.5rem 0;">
                            @endif
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="dropdown-item" style="color: var(--danger); font-weight: 600;">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- Login and Register Buttons (Only for guests) -->
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm rounded-pill">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-light btn-sm rounded-pill" style="color: var(--primary-green-dark);">
                        <i class="fas fa-user-plus me-1"></i> Register
                    </a>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- flash messages are handled in flash-messages.blade.php component --}}
