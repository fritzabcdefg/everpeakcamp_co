<footer class="mt-5" style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: #fff;">
    <div class="container py-5">
        <div class="row g-4">
            <!-- Brand Section -->
            <div class="col-lg-3 col-md-6">
                <div class="mb-3">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-campground text-warning"></i> EverPeak Camp Co.
                    </h5>
                    <p class="text-light" style="font-size: 0.95rem;">
                        Your ultimate destination for premium outdoor and camping gear. Explore the world with confidence.
                    </p>
                </div>
                <div>
                    <h6 class="fw-bold mb-2 text-warning">Follow Us</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light text-decoration-none" title="Facebook">
                            <i class="fab fa-facebook fa-lg"></i>
                        </a>
                        <a href="#" class="text-light text-decoration-none" title="Instagram">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
                        <a href="#" class="text-light text-decoration-none" title="Twitter">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
                        <a href="#" class="text-light text-decoration-none" title="YouTube">
                            <i class="fab fa-youtube fa-lg"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3 text-warning">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-light text-decoration-none" style="transition: color 0.3s;">
                            <i class="fas fa-home fa-sm me-2"></i> Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('products.index') }}" class="text-light text-decoration-none" style="transition: color 0.3s;">
                            <i class="fas fa-box fa-sm me-2"></i> All Products
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('categories.index') }}" class="text-light text-decoration-none" style="transition: color 0.3s;">
                            <i class="fas fa-folder fa-sm me-2"></i> Categories
                        </a>
                    </li>
                    @auth
                        <li class="mb-2">
                            <a href="{{ route('cart.index') }}" class="text-light text-decoration-none" style="transition: color 0.3s;">
                                <i class="fas fa-shopping-cart fa-sm me-2"></i> My Cart
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('orders.index') }}" class="text-light text-decoration-none" style="transition: color 0.3s;">
                                <i class="fas fa-receipt fa-sm me-2"></i> My Orders
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3 text-warning">Customer Service</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none" style="transition: color 0.3s;">
                            <i class="fas fa-question-circle fa-sm me-2"></i> FAQs
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none" style="transition: color 0.3s;">
                            <i class="fas fa-undo fa-sm me-2"></i> Returns & Exchanges
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none" style="transition: color 0.3s;">
                            <i class="fas fa-shield-alt fa-sm me-2"></i> Privacy Policy
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none" style="transition: color 0.3s;">
                            <i class="fas fa-file-contract fa-sm me-2"></i> Terms & Conditions
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3 text-warning">Get in Touch</h6>
                <div class="mb-3">
                    <p class="mb-2 text-light" style="font-size: 0.95rem;">
                        <i class="fas fa-phone text-warning me-2"></i> +63-966-425-9993
                    </p>
                    <p class="mb-2 text-light" style="font-size: 0.95rem;">
                        <i class="fas fa-envelope text-warning me-2"></i> <a href="mailto:business@everpeak.com" class="text-light text-decoration-none">info@everpeak.com</a>
                    </p>
                    <p class="mb-2 text-light" style="font-size: 0.95rem;">
                        <i class="fas fa-map-marker-alt text-warning me-2"></i> New Lower Bicutan, Taguig City, Philippines
                    </p>
                </div>
                <div>
                    <h6 class="fw-bold mb-2 text-warning">Hours</h6>
                    <p class="text-light mb-0" style="font-size: 0.95rem;">Mon - Fri: 9 AM - 6 PM</p>
                    <p class="text-light" style="font-size: 0.95rem;">Sat - Sun: 10 AM - 4 PM</p>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <hr style="background-color: rgba(255, 255, 255, 0.2); border: none; height: 1px; margin: 2rem 0;">

        <!-- Bottom Section -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-light mb-0" style="font-size: 0.9rem;">
                    <i class="fas fa-copyright me-1"></i> 2026 EverPeak Camp Co. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-light mb-0" style="font-size: 0.9rem;">
                    Designed with <i class="fas fa-heart text-danger"></i> for outdoor enthusiasts
                </p>
            </div>
        </div>
    </div>

    <style>
        footer a:hover {
            color: #ffc107 !important;
            text-decoration: underline !important;
        }
    </style>
</footer>
