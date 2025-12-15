// Restaurant Order System - Main JavaScript File

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Cart count update
    updateCartCount();

    // Initialize image zoom lightbox for product images
    if (typeof initImageZoom === 'function') {
        initImageZoom();
    }
});

// Global functions
window.addToCart = function(productId, quantity = 1) {
    fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount(data.cartCount);
                showNotification('success', data.message);
            } else {
                showNotification('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('danger', 'Có lỗi xảy ra, vui lòng thử lại');
        });
};

window.updateCartCount = function(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (!cartCountElement) {
        // Cart count element doesn't exist on this page (e.g., admin pages)
        return;
    }

    if (count !== undefined) {
        cartCountElement.textContent = count;
    } else {
        // Fetch current cart count
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                if (cartCountElement) {
                    cartCountElement.textContent = data.cartCount || 0;
                }
            })
            .catch(error => {
                console.error('Error fetching cart count:', error);
            });
    }
};

// Image zoom lightbox
window.initImageZoom = function() {
    const backdrop = document.getElementById('imageLightbox');
    const imgTarget = document.getElementById('imageLightboxImg');
    if (!backdrop || !imgTarget) return;

    // Delegate clicks for all zoomable images (img tag)
    document.querySelectorAll('img.zoomable-image').forEach(img => {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', () => {
            const src = img.getAttribute('data-full') || img.src;
            imgTarget.src = src;
            backdrop.classList.add('active');
        });
    });

    // Delegate clicks for background-image blocks (e.g. Ao Dai story)
    document.querySelectorAll('.zoomable-bg').forEach(box => {
        box.style.cursor = 'zoom-in';
        box.addEventListener('click', () => {
            const src = box.getAttribute('data-full');
            if (!src) return;
            imgTarget.src = src;
            backdrop.classList.add('active');
        });
    });

    // Close on click backdrop
    backdrop.addEventListener('click', () => {
        backdrop.classList.remove('active');
        imgTarget.src = '';
    });
};

window.showNotification = function(type, message, duration = 3000) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 350px; min-width: 250px;';
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${getIconForType(type)} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto remove after duration
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, duration);
};

function getIconForType(type) {
    const icons = {
        'success': 'check-circle',
        'danger': 'exclamation-triangle',
        'warning': 'exclamation-circle',
        'info': 'info-circle'
    };
    return icons[type] || 'info-circle';
}

// Form validation helpers
window.validateForm = function(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    return isValid;
};

// Price formatting
window.formatPrice = function(price) {
    return new Intl.NumberFormat('vi-VN').format(price) + 'đ';
};

// Image lazy loading
window.lazyLoadImages = function() {
    const images = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
};

// Search functionality
window.initSearch = function() {
    const searchForm = document.querySelector('form[action="/search"]');
    const searchInput = searchForm?.querySelector('input[name="q"]');

    if (!searchInput) return;

    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);

        if (this.value.length >= 2) {
            searchTimeout = setTimeout(() => {
                // Auto-suggest functionality could go here
                console.log('Searching for:', this.value);
            }, 300);
        }
    });
};

// Quantity controls
window.setupQuantityControls = function() {
    document.querySelectorAll('.quantity-control').forEach(control => {
        const minusBtn = control.querySelector('.quantity-minus');
        const plusBtn = control.querySelector('.quantity-plus');
        const input = control.querySelector('.quantity-input');

        if (minusBtn && input) {
            minusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value) || 1;
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    input.dispatchEvent(new Event('change'));
                }
            });
        }

        if (plusBtn && input) {
            plusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value) || 1;
                const maxValue = parseInt(input.max) || 99;
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    input.dispatchEvent(new Event('change'));
                }
            });
        }
    });
};

// Loading states
window.showLoading = function(element) {
    if (typeof element === 'string') {
        element = document.getElementById(element);
    }

    if (element) {
        element.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border spinner-border-sm" role="status"></div></div>';
        element.setAttribute('disabled', 'disabled');
    }
};

window.hideLoading = function(element, originalContent) {
    if (typeof element === 'string') {
        element = document.getElementById(element);
    }

    if (element) {
        element.innerHTML = originalContent;
        element.removeAttribute('disabled');
    }
};

// Local storage helpers
window.saveToStorage = function(key, data) {
    try {
        localStorage.setItem(key, JSON.stringify(data));
    } catch (error) {
        console.error('Error saving to localStorage:', error);
    }
};

window.loadFromStorage = function(key) {
    try {
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : null;
    } catch (error) {
        console.error('Error loading from localStorage:', error);
        return null;
    }
};

// Initialize components based on page
window.initPageSpecific = function() {
    const currentPath = window.location.pathname;

    // Product listing page
    if (currentPath === '/' || currentPath.includes('/category')) {
        lazyLoadImages();
        initSearch();
    }

    // Cart page
    if (currentPath === '/cart') {
        setupQuantityControls();
    }

    // Product detail page
    if (currentPath.includes('/product')) {
        setupQuantityControls();
    }

    // Admin pages
    if (currentPath.includes('/admin')) {
        initAdminFeatures();
    }
};

window.initAdminFeatures = function() {
    // Confirm delete actions
    document.querySelectorAll('[data-confirm-delete]').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Bạn có chắc chắn muốn xóa? Hành động này không thể hoàn tác.')) {
                e.preventDefault();
            }
        });
    });

    // Auto-save form data
    document.querySelectorAll('form[data-auto-save]').forEach(form => {
        const inputs = form.querySelectorAll('input, select, textarea');

        inputs.forEach(input => {
            input.addEventListener('change', function() {
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);
                saveToStorage(`form_${form.id}`, data);
            });
        });

        // Restore form data on load
        const savedData = loadFromStorage(`form_${form.id}`);
        if (savedData) {
            Object.keys(savedData).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = savedData[key];
                }
            });
        }
    });
};

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initPageSpecific();
});

// Handle network errors
window.addEventListener('online', function() {
    showNotification('success', 'Kết nối internet đã được khôi phục');
});

window.addEventListener('offline', function() {
    showNotification('warning', 'Mất kết nối internet. Một số tính năng có thể không hoạt động.');
});

// Export for use in other scripts
window.RestaurantApp = {
    addToCart,
    updateCartCount,
    showNotification,
    validateForm,
    formatPrice,
    lazyLoadImages,
    initSearch,
    setupQuantityControls,
    showLoading,
    hideLoading,
    saveToStorage,
    loadFromStorage
};