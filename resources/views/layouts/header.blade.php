<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-campground"></i> EverPeak Camp Co.
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <!-- Search -->
                <form action="/products" class="d-flex me-3" method="GET">
                    <input class="form-control form-control-sm me-2" type="search" 
                           placeholder="Search products..." aria-label="Search" name="search" style="width: 200px;">
                    <button class="btn btn-outline-primary btn-sm" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <!-- Cart -->
                @if (!Auth::check() || Auth::user()->role !== 'admin')
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-success position-relative me-2">
                        <i class="fas fa-shopping-cart"></i> Cart
                        @php
                            $cartCount = Auth::check() ? \App\Models\CartItem::where('user_id', Auth::id())->count() : 0;
                        @endphp
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                @endif

                <!-- Account Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle"></i>
                        {{ Auth::check() ? Auth::user()->name : 'Account' }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <h6 class="dropdown-header"><strong>Admin Panel</strong></h6>
                                <a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard</a>
                                <a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users"></i> Manage Users</a>
                                <a class="dropdown-item" href="{{ route('products.index') }}"><i class="fas fa-box"></i> Manage Products</a>
                                <a class="dropdown-item" href="{{ route('categories.index') }}"><i class="fas fa-folder"></i> Manage Categories</a>
                                <a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-receipt"></i> Manage Orders</a>
                                <hr class="dropdown-divider">
                                <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}"><i class="fas fa-user"></i> My Profile</a>
                            @else
                                <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}"><i class="fas fa-user"></i> My Profile</a>
                                <a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-receipt"></i> My Orders</a>
                                <a class="dropdown-item" href="{{ route('cart.index') }}"><i class="fas fa-shopping-cart"></i> Shopping Cart</a>
                            @endif
                            <hr class="dropdown-divider">
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        @else
                            <a class="dropdown-item" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Register</a>
                            <a class="dropdown-item" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a>
                        @endauth
                    </div>
                </li>
            </div>
        </div>
    </div>
</nav>
