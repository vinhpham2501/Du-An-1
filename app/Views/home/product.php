<?php use App\Helpers\ImageHelper; $title = htmlspecialchars($product['name']) . ' - Sắc Việt'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6">
            <?php 
                $mainImageSrc = ImageHelper::getImageSrc($product['image_url'] ?? null);
            ?>
            <?php if (!empty($mainImageSrc)): ?>
                <img src="<?= htmlspecialchars($mainImageSrc) ?>" 
                     class="img-fluid rounded" alt="<?= htmlspecialchars($product['name']) ?>">
            <?php else: ?>
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-shirt fa-5x text-muted"></i>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-6">
            <h1 class="mb-3"><?= htmlspecialchars($product['name']) ?></h1>
            
            <div class="mb-3">
                <?php if ($product['sale_price']): ?>
                    <span class="text-muted text-decoration-line-through h5">
                        <?= number_format($product['price']) ?>đ
                    </span>
                    <span class="text-danger fw-bold h3 ms-2">
                        <?= number_format($product['sale_price']) ?>đ
                    </span>
                    <span class="badge bg-danger ms-2">
                        Giảm <?= round((($product['price'] - $product['sale_price']) / $product['price']) * 100) ?>%
                    </span>
                <?php else: ?>
                    <span class="fw-bold h3">
                        <?= number_format($product['price']) ?>đ
                    </span>
                <?php endif; ?>
            </div>
            
            <p class="text-muted mb-3">Đơn vị: <?= htmlspecialchars($product['unit']) ?></p>
            
            <?php if ($product['description']): ?>
                <div class="mb-4">
                    <h5>Mô tả</h5>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($product['status'] === 'available'): ?>
                <div class="mb-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <label class="form-label">Số lượng:</label>
                        </div>
                        <div class="col-auto">
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="99">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button class="btn btn-primary btn-lg" onclick="addToCart(<?= $product['id'] ?>)">
                    <i class="fas fa-cart-plus me-2"></i>
                    Thêm vào giỏ hàng
                </button>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Sản phẩm hiện tại không có sẵn
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3>Đánh giá từ khách hàng</h3>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Viết đánh giá</h5>
                        <form id="review-form">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Đánh giá:</label>
                                <div class="rating">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>">
                                        <label for="star<?= $i ?>" class="star">★</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nhận xét:</label>
                                <textarea class="form-control" name="comment" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (empty($reviews)): ?>
                <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="card mb-3 review-item" data-review-id="<?= $review['id'] ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="mb-0"><?= htmlspecialchars($review['user_name']) ?></h6>
                                    <?php if (isset($_SESSION['user_id']) && $review['user_id'] == $_SESSION['user_id']): ?>
                                        <span class="badge bg-info">Đánh giá của bạn</span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></small>
                                    <?php if (isset($_SESSION['user_id']) && $review['user_id'] == $_SESSION['user_id']): ?>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editReview(<?= $review['id'] ?>, <?= $review['rating'] ?>, '<?= htmlspecialchars(addslashes($review['comment'] ?? '')) ?>')" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteReview(<?= $review['id'] ?>)" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-2 review-rating-display">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="review-comment-display">
                                <?php if ($review['comment']): ?>
                                    <p class="mb-2"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Edit form (hidden by default) -->
                            <div class="review-edit-form d-none mt-3 p-3 border rounded bg-light">
                                <h6 class="mb-2">Chỉnh sửa đánh giá</h6>
                                <div class="mb-2">
                                    <label class="form-label mb-1">Chọn số sao:</label>
                                    <div class="edit-review-stars text-warning fs-5">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="far fa-star edit-review-star" data-value="<?= $i ?>" onclick="setEditReviewRating(<?= $i ?>)"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <input type="hidden" class="edit-review-rating" value="0">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label mb-1">Nhận xét:</label>
                                    <textarea class="form-control edit-review-comment" rows="3" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."></textarea>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="saveReview(<?= $review['id'] ?>)">Lưu</button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="cancelEditReview(<?= $review['id'] ?>)">Hủy</button>
                                </div>
                            </div>
                            
                            <?php if (!empty($review['reply'])): ?>
                                <div class="mt-3 p-3 rounded" style="background: #fff; border: 1px solid #e0e0e0; border-left: 3px solid #8b0000; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #8b0000, #5a0000); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; margin-right: 10px;">
                                                <i class="fas fa-headset"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block" style="font-size: 13px; line-height: 1.3; color: #8b0000;"><?= htmlspecialchars($review['replied_by'] ?? 'Shop') ?></strong>
                                                <?php if (!empty($review['reply_date'])): ?>
                                                    <small class="text-muted d-block" style="font-size: 11px; margin-top: 2px;"><?= date('d/m/Y H:i', strtotime($review['reply_date'])) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <span class="badge rounded-pill" style="background-color: #8b0000; color: white; font-size: 10px; padding: 5px 10px; font-weight: 500;">
                                            <i class="fas fa-reply me-1"></i>Phản hồi từ shop
                                        </span>
                                    </div>
                                    <div style="font-size: 14px; line-height: 1.7; color: #333; margin-top: 8px;">
                                        <?= nl2br(htmlspecialchars($review['reply'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h3>Sản phẩm liên quan</h3>
                <div class="row">
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <?php if ($relatedProduct['id'] != $product['id']): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="card h-100">
                                    <?php 
                                        $relatedImageSrc = ImageHelper::getImageSrc($relatedProduct['image_url'] ?? null);
                                    ?>
                                    <?php if (!empty($relatedImageSrc)): ?>
                                        <img src="<?= htmlspecialchars($relatedImageSrc) ?>" 
                                             class="card-img-top" style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-shirt fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title">
                                            <a href="/product/<?= $relatedProduct['id'] ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($relatedProduct['name']) ?>
                                            </a>
                                        </h6>
                                        
                                        <div class="mt-auto">
                                            <div class="mb-2">
                                                <?php if ($relatedProduct['sale_price']): ?>
                                                    <span class="text-muted text-decoration-line-through small">
                                                        <?= number_format($relatedProduct['price']) ?>đ
                                                    </span><br>
                                                    <span class="text-danger fw-bold">
                                                        <?= number_format($relatedProduct['sale_price']) ?>đ
                                                    </span>
                                                <?php else: ?>
                                                    <span class="fw-bold">
                                                        <?= number_format($relatedProduct['price']) ?>đ
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if ($relatedProduct['status'] === 'available'): ?>
                                                <button class="btn btn-sm btn-primary w-100" onclick="addToCart(<?= $relatedProduct['id'] ?>)">
                                                    Thêm vào giỏ
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary w-100" disabled>
                                                    Hết hàng
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    margin-right: 0.1rem;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffc107;
}
</style>

<script>
function changeQuantity(delta) {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    let newValue = currentValue + delta;
    
    if (newValue >= 1 && newValue <= 99) {
        quantityInput.value = newValue;
    }
}

function addToCart(productId) {
    const quantity = document.getElementById('quantity').value;
    
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

// Edit review functions
function editReview(reviewId, currentRating, currentComment) {
    const reviewItem = document.querySelector(`.review-item[data-review-id="${reviewId}"]`);
    if (!reviewItem) return;
    
    const editForm = reviewItem.querySelector('.review-edit-form');
    const displayComment = reviewItem.querySelector('.review-comment-display');
    const displayRating = reviewItem.querySelector('.review-rating-display');
    
    if (!editForm) return;
    
    // Show edit form, hide display
    editForm.classList.remove('d-none');
    displayComment.style.display = 'none';
    displayRating.style.display = 'none';
    
    // Set current values
    const ratingInput = editForm.querySelector('.edit-review-rating');
    const commentInput = editForm.querySelector('.edit-review-comment');
    const stars = editForm.querySelectorAll('.edit-review-star');
    
    if (ratingInput) ratingInput.value = currentRating;
    if (commentInput) commentInput.value = currentComment;
    
    // Update star display
    stars.forEach((star, index) => {
        if (index + 1 <= currentRating) {
            star.classList.remove('far');
            star.classList.add('fas');
        } else {
            star.classList.remove('fas');
            star.classList.add('far');
        }
    });
}

function setEditReviewRating(value) {
    const editForm = event.target.closest('.review-edit-form');
    if (!editForm) return;
    
    const ratingInput = editForm.querySelector('.edit-review-rating');
    const stars = editForm.querySelectorAll('.edit-review-star');
    
    if (ratingInput) ratingInput.value = value;
    
    stars.forEach((star, index) => {
        if (index + 1 <= value) {
            star.classList.remove('far');
            star.classList.add('fas');
        } else {
            star.classList.remove('fas');
            star.classList.add('far');
        }
    });
}

function saveReview(reviewId) {
    const reviewItem = document.querySelector(`.review-item[data-review-id="${reviewId}"]`);
    if (!reviewItem) return;
    
    const editForm = reviewItem.querySelector('.review-edit-form');
    if (!editForm) return;
    
    const ratingInput = editForm.querySelector('.edit-review-rating');
    const commentInput = editForm.querySelector('.edit-review-comment');
    
    if (!ratingInput || !commentInput) return;
    
    const rating = parseInt(ratingInput.value) || 0;
    const comment = commentInput.value.trim();
    
    if (!rating || rating < 1 || rating > 5) {
        alert('Vui lòng chọn số sao (1-5).');
        return;
    }
    
    const formData = new URLSearchParams();
    formData.append('review_id', reviewId);
    formData.append('rating', rating);
    formData.append('comment', comment);
    
    fetch('/update-review', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
        }
    })
    .catch(() => {
        alert('Có lỗi xảy ra, vui lòng thử lại!');
    });
}

function cancelEditReview(reviewId) {
    const reviewItem = document.querySelector(`.review-item[data-review-id="${reviewId}"]`);
    if (!reviewItem) return;
    
    const editForm = reviewItem.querySelector('.review-edit-form');
    const displayComment = reviewItem.querySelector('.review-comment-display');
    const displayRating = reviewItem.querySelector('.review-rating-display');
    
    if (!editForm) return;
    
    // Hide edit form, show display
    editForm.classList.add('d-none');
    if (displayComment) displayComment.style.display = '';
    if (displayRating) displayRating.style.display = '';
}

function deleteReview(reviewId) {
    if (!confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) {
        return;
    }
    
    const formData = new URLSearchParams();
    formData.append('review_id', reviewId);
    
    fetch('/delete-review', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
        }
    })
    .catch(() => {
        alert('Có lỗi xảy ra, vui lòng thử lại!');
    });
}

// Review form submission
document.getElementById('review-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/add-review', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'Có lỗi xảy ra, vui lòng thử lại');
    });
});

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
</script>
