/* ============================================
   NATURE-THEMED INTERACTIVE ENHANCEMENTS
   ============================================ */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all interactive features
    observeElements();
    initializeTooltips();
    addScrollEffects();
    enhanceCardInteractions();
    initializeFormValidation();
    initializeProductFilters();
    smoothScroll();
});

/* ============================================
   SCROLL ANIMATIONS
   ============================================ */

function observeElements() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all cards, product items, and section headers
    document.querySelectorAll('.card, .product-card, .section-header, .product-info').forEach(el => {
        observer.observe(el);
    });
}

/* ============================================
   SCROLL EFFECT HANDLER
   ============================================ */

function addScrollEffects() {
    let scrollTimer;
    let lastScrollTop = 0;
    const navbar = document.querySelector('.navbar');

    if (!navbar) return;

    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimer);
        
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Add shadow on scroll
        if (scrollTop > 10) {
            navbar.style.boxShadow = '0 8px 24px rgba(26, 71, 42, 0.2)';
        } else {
            navbar.style.boxShadow = '0 4px 12px rgba(26, 71, 42, 0.15)';
        }
        
        lastScrollTop = scrollTop;
    });
}

/* ============================================
   ENHANCED CARD INTERACTIONS
   ============================================ */

function enhanceCardInteractions() {
    const cards = document.querySelectorAll('.card, .product-card');
    
    cards.forEach(card => {
        // Magnetic effect on hover
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;
            
            this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });

        // Hover effect for product images
        const img = card.querySelector('img');
        if (img) {
            card.addEventListener('mouseenter', function() {
                img.style.filter = 'brightness(1.1)';
            });
            card.addEventListener('mouseleave', function() {
                img.style.filter = 'brightness(1)';
            });
        }
    });
}

/* ============================================
   FORM VALIDATION
   ============================================ */

function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Bootstrap validation
            if (!this.checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });

        // Real-time validation feedback
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('change', function() {
                validateField(this);
            });
        });
    });
}

function validateField(field) {
    const formGroup = field.closest('.form-group') || field.closest('.mb-3');
    
    if (!formGroup) return;

    // Remove existing feedback
    const existingFeedback = formGroup.querySelector('.valid-feedback, .invalid-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }

    let isValid = field.checkValidity();
    
    // Custom validation
    if (field.type === 'email') {
        isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
    } else if (field.type === 'password' && field.name === 'confirm_password') {
        const passwordField = document.querySelector('input[name="password"]');
        isValid = field.value === passwordField.value;
    }

    if (field.value === '') {
        field.classList.remove('is-valid', 'is-invalid');
    } else if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
    } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
    }
}

/* ============================================
   TOOLTIPS & POPOVERS
   ============================================ */

function initializeTooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    popoverTriggerList.forEach(popoverTriggerEl => {
        new bootstrap.Popover(popoverTriggerEl);
    });
}

/* ============================================
   SMOOTH SCROLL
   ============================================ */

function smoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/* ============================================
   PRODUCT FILTERS
   ============================================ */

function initializeProductFilters() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            filterProducts(filter);
            
            // Update active state
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

function filterProducts(category) {
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const productCategory = product.getAttribute('data-category');
        
        if (category === 'all' || productCategory === category) {
            product.style.display = 'block';
            setTimeout(() => {
                product.classList.add('animate-fade-in');
            }, 10);
        } else {
            product.classList.remove('animate-fade-in');
            product.style.display = 'none';
        }
    });
}

/* ============================================
   CART FUNCTIONALITY
   ============================================ */

function updateCartBadge() {
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        // This will be updated via AJAX or form submission
        const count = parseInt(cartBadge.textContent);
        cartBadge.textContent = count + 1;
        cartBadge.classList.add('animate-pulse');
        
        setTimeout(() => {
            cartBadge.classList.remove('animate-pulse');
        }, 1000);
    }
}

/* ============================================
   QUANTITY CONTROLS
   ============================================ */

function initializeQuantityControls() {
    const decreaseButtons = document.querySelectorAll('[data-quantity-decrease]');
    const increaseButtons = document.querySelectorAll('[data-quantity-increase]');
    
    decreaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.nextElementSibling;
            let value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
                updateRowTotal(this.closest('tr'));
            }
        });
    });
    
    increaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            let value = parseInt(input.value);
            input.value = value + 1;
            updateRowTotal(this.closest('tr'));
        });
    });
}

function updateRowTotal(row) {
    const quantity = parseInt(row.querySelector('[data-quantity-input]').value);
    const price = parseFloat(row.getAttribute('data-price'));
    const total = quantity * price;
    
    const totalCell = row.querySelector('[data-row-total]');
    if (totalCell) {
        totalCell.textContent = '₱' + total.toFixed(2);
    }
    
    updateCartTotal();
}

function updateCartTotal() {
    const rows = document.querySelectorAll('tr[data-price]');
    let total = 0;
    
    rows.forEach(row => {
        const quantity = parseInt(row.querySelector('[data-quantity-input]').value);
        const price = parseFloat(row.getAttribute('data-price'));
        total += quantity * price;
    });
    
    const totalElement = document.querySelector('[data-cart-total]');
    if (totalElement) {
        totalElement.textContent = '₱' + total.toFixed(2);
    }
}

/* ============================================
   IMAGE LAZY LOADING
   ============================================ */

function initializeLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

/* ============================================
   PRICE FORMATTING
   ============================================ */

function formatPrice(price) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    }).format(price);
}

/* ============================================
   NOTIFICATION SYSTEM
   ============================================ */

function showNotification(message, type = 'success', duration = 3000) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show animate-slide-in-up`;
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    const container = document.querySelector('main') || document.body;
    container.insertBefore(alertDiv, container.firstChild);

    if (duration > 0) {
        setTimeout(() => {
            alertDiv.remove();
        }, duration);
    }
}

/* ============================================
   MODAL ENHANCEMENTS
   ============================================ */

function initializeModals() {
    const modals = document.querySelectorAll('.modal');
    
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            this.classList.add('animate-fade-in');
        });
    });
}

/* ============================================
   SEARCH FUNCTIONALITY
   ============================================ */

function initializeSearch() {
    const searchInput = document.querySelector('[data-search]');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const items = document.querySelectorAll('[data-searchable]');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = '';
                    item.classList.add('animate-fade-in');
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
}

/* ============================================
   STAR RATING
   ============================================ */

function initializeRatings() {
    const stars = document.querySelectorAll('[data-rating-star]');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating-value');
            const form = this.closest('form');
            
            if (form) {
                const input = form.querySelector('input[name="rating"]');
                if (input) {
                    input.value = rating;
                }
            }
            
            // Update star display
            stars.forEach(s => {
                if (parseInt(s.getAttribute('data-rating-value')) <= rating) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });
    });
}

/* ============================================
   INITIALIZE ON PAGE LOAD
   ============================================ */

// Re-initialize quantity controls when needed
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeQuantityControls);
} else {
    initializeQuantityControls();
}

// Initialize other features
window.addEventListener('load', function() {
    initializeLazyLoading();
    initializeModals();
    initializeSearch();
    initializeRatings();
});
