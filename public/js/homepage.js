// Homepage JavaScript Functions

// Apply sorting function
function applySorting() {
    const sortValue = document.getElementById('sort-select').value;
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort', sortValue);
    window.location.search = urlParams.toString();
}

// Add to cart function
function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cart-count').textContent = data.cartCount;
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
    });
}

// Show alert function
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Initialize scroll animations first
    initScrollAnimations();
    
    // Initialize other functions
    // initializeFilters();
    // initializePagination();
    // initializeSearch();
    
    // Initialize hero slider if exists
    if (document.querySelector('.hero-slider')) {
        initHeroSlider();
    }
    
    // Handle image loading errors
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function() {
            this.src = '/images/placeholder-food.jpg';
        });
    });
    
    // Initialize Bootstrap carousel
    const carousel = document.getElementById('featuredDishesCarousel');
    if (carousel) {
        const bsCarousel = new bootstrap.Carousel(carousel, {
            interval: 5000,
            wrap: true,
            pause: 'hover'
        });
    }
    
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
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
});

// Scroll animations for smooth reveal effects
function initScrollAnimations() {
    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .fade-in-left {
            opacity: 0;
            transform: translateX(-30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .fade-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }
        
        .fade-in-right {
            opacity: 0;
            transform: translateX(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .fade-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }
        
        .scale-in {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .scale-in.visible {
            opacity: 1;
            transform: scale(1);
        }
        
        /* Stagger animation delays for product cards */
        .product-card:nth-child(1) { transition-delay: 0.1s; }
        .product-card:nth-child(2) { transition-delay: 0.2s; }
        .product-card:nth-child(3) { transition-delay: 0.3s; }
        .product-card:nth-child(4) { transition-delay: 0.4s; }
        .product-card:nth-child(5) { transition-delay: 0.5s; }
        .product-card:nth-child(6) { transition-delay: 0.6s; }
        .product-card:nth-child(7) { transition-delay: 0.7s; }
        .product-card:nth-child(8) { transition-delay: 0.8s; }
        .product-card:nth-child(9) { transition-delay: 0.9s; }
        .product-card:nth-child(10) { transition-delay: 1.0s; }
        .product-card:nth-child(11) { transition-delay: 1.1s; }
        .product-card:nth-child(12) { transition-delay: 1.2s; }
        
        /* Hero section animations */
        .hero-content h1 { transition-delay: 0.2s; }
        .hero-content p { transition-delay: 0.4s; }
        .hero-content .btn:nth-child(1) { transition-delay: 0.6s; }
        .hero-content .btn:nth-child(2) { transition-delay: 0.7s; }
    `;
    document.head.appendChild(style);
    
    // Add animation classes to elements
    addAnimationClasses();
    
    // Create intersection observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    // Observe all animated elements
    document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right, .scale-in').forEach(el => {
        observer.observe(el);
    });
}

function addAnimationClasses() {
    // Hero section elements
    const heroTitle = document.querySelector('.hero-content h1, .hero-slide h1');
    const heroText = document.querySelector('.hero-content p, .hero-slide p');
    const heroButtons = document.querySelectorAll('.hero-content .btn, .hero-slide .btn');
    
    if (heroTitle) heroTitle.classList.add('fade-in-up');
    if (heroText) heroText.classList.add('fade-in-up');
    heroButtons.forEach(btn => btn.classList.add('fade-in-up'));
    
    // Section titles and subtitles
    document.querySelectorAll('h2, .section-title, .display-4, .display-5').forEach(title => {
        title.classList.add('fade-in-up');
    });
    
    document.querySelectorAll('.lead, .section-subtitle').forEach(subtitle => {
        subtitle.classList.add('fade-in-up');
    });
    
    // Product cards with different animations for different sections
    document.querySelectorAll('.sale-products .product-card').forEach((card, index) => {
        if (index % 2 === 0) {
            card.classList.add('fade-in-left');
        } else {
            card.classList.add('fade-in-right');
        }
    });
    
    document.querySelectorAll('.new-products .product-card').forEach(card => {
        card.classList.add('fade-in-up');
    });
    
    document.querySelectorAll('.top-selling .product-card').forEach(card => {
        card.classList.add('scale-in');
    });
    
    // Default product cards (for main product listing)
    document.querySelectorAll('.product-grid .product-card, .products-container .product-card').forEach(card => {
        card.classList.add('fade-in-up');
    });
    
    // Stats and feature cards
    document.querySelectorAll('.stat-card, .stats-item').forEach(card => {
        card.classList.add('scale-in');
    });
    
    document.querySelectorAll('.feature-card').forEach(card => {
        card.classList.add('fade-in-up');
    });
    
    // Other elements
    document.querySelectorAll('.testimonial, .review-card').forEach(card => {
        card.classList.add('fade-in-up');
    });
}

// Hero slider initialization
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.hero-indicator');
    let currentSlide = 0;
    
    if (slides.length === 0) return;
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    // Auto advance slides every 5 seconds
    setInterval(nextSlide, 5000);
    
    // Indicator clicks
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
}
