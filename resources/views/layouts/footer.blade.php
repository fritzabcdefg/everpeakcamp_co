<footer>
    <div class="container py-5">
        <div class="row g-4">
            <!-- Brand Section -->
            <div class="col-lg-3 col-md-6">
                <div class="mb-3">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-campground" style="color: var(--terracotta);"></i> Everpeak Camp Co
                    </h5>
                    <p style="font-size: 0.95rem;">
                        Your ultimate destination for premium outdoor and camping gear. Explore the world with confidence.
                    </p>
                </div>
                <div>
                    <h6 class="fw-bold mb-2">Follow Us</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-decoration-none transition-smooth" title="Facebook">
                            <i class="fab fa-facebook fa-lg"></i>
                        </a>
                        <a href="#" class="text-decoration-none transition-smooth" title="Instagram">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
                        <a href="#" class="text-decoration-none transition-smooth" title="Twitter">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
                        <a href="#" class="text-decoration-none transition-smooth" title="YouTube">
                            <i class="fab fa-youtube fa-lg"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-decoration-none transition-smooth">
                            <i class="fas fa-home fa-sm me-2"></i> Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('products.index') }}" class="text-decoration-none transition-smooth">
                            <i class="fas fa-box fa-sm me-2"></i> All Products
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('categories.index') }}" class="text-decoration-none transition-smooth">
                            <i class="fas fa-folder fa-sm me-2"></i> Categories
                        </a>
                    </li>
                    @auth
                        <li class="mb-2">
                            <a href="{{ route('cart.index') }}" class="text-decoration-none transition-smooth">
                                <i class="fas fa-shopping-cart fa-sm me-2"></i> My Cart
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('orders.index') }}" class="text-decoration-none transition-smooth">
                                <i class="fas fa-receipt fa-sm me-2"></i> My Orders
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Customer Service</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none transition-smooth">
                            <i class="fas fa-question-circle fa-sm me-2"></i> FAQs
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none transition-smooth">
                            <i class="fas fa-undo fa-sm me-2"></i> Returns & Exchanges
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none transition-smooth">
                            <i class="fas fa-shield-alt fa-sm me-2"></i> Privacy Policy
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none transition-smooth">
                            <i class="fas fa-file-contract fa-sm me-2"></i> Terms & Conditions
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Get in Touch</h6>
                <div class="mb-3">
                    <p class="mb-2" style="font-size: 0.95rem;">
                        <i class="fas fa-phone" style="color: var(--terracotta);"></i> +63-966-425-9993
                    </p>
                    <p class="mb-2" style="font-size: 0.95rem;">
                        <i class="fas fa-envelope" style="color: var(--terracotta);"></i> <a href="mailto:info@everpeak.com" class="text-decoration-none">info@everpeak.com</a>
                    </p>
                    <p class="mb-2" style="font-size: 0.95rem;">
                        <i class="fas fa-map-marker-alt" style="color: var(--terracotta);"></i> New Lower Bicutan, Taguig City, Philippines
                    </p>
                </div>
                <div>
                    <h6 class="fw-bold mb-2">Hours</h6>
                    <p class="mb-0" style="font-size: 0.95rem;">Mon - Fri: 9 AM - 6 PM</p>
                    <p style="font-size: 0.95rem;">Sat - Sun: 10 AM - 4 PM</p>
                </div>
            </div>
        </div>

        <div class="footer-divider"></div>

        <!-- Bottom Section -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0" style="font-size: 0.9rem;">
                    <i class="fas fa-copyright me-1"></i> 2026 Everpeak Camp Co. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0" style="font-size: 0.9rem;">
                    Designed with <i class="fas fa-heart" style="color: var(--danger);"></i> for outdoor enthusiasts
                </p>
            </div>
        </div>
    </div>
</footer>
