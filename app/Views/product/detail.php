<?php use App\Helpers\ImageHelper; $title = htmlspecialchars($product['name']) . ' - Chi tiết sản phẩm'; ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="py-3">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/products?category_id=<?= $product['category_id'] ?>" class="text-decoration-none"><?= htmlspecialchars($product['category_name']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </div>
</nav>

<!-- Product Detail Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-6 mb-4">
                <div class="product-image-container">
                    <?php if (!empty($images)): ?>
                        <!-- Main Image -->
                        <div class="position-relative" style="width: 100%; height: 500px;">
                            <img id="main-image" 
                                 src="<?= htmlspecialchars(\App\Helpers\ImageHelper::getImageSrc($images[0]['image_url'])) ?>" 
                                 class="img-fluid rounded shadow-lg w-100 h-100 zoomable-image" 
                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                 style="object-fit: contain; background: #f8f9fa;"
                                 loading="eager" decoding="async">
                        </div>
                        
                        <!-- Thumbnail Gallery -->
                        <div class="d-flex gap-2 mt-3 overflow-auto">
                            <?php foreach ($images as $index => $img): ?>
                                <img src="<?= htmlspecialchars(\App\Helpers\ImageHelper::getImageSrc($img['image_url'])) ?>"
                                     class="img-thumbnail cursor-pointer <?= $index === 0 ? 'border-primary' : '' ?>"
                                     style="width: 80px; height: 80px; object-fit: contain; background: #f8f9fa;"
                                     onclick="changeMainImage('<?= htmlspecialchars(\App\Helpers\ImageHelper::getImageSrc($img['image_url'])) ?>', this)"
                                     alt="Thumbnail <?= $index + 1 ?>">
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-lg" 
                             style="width: 100%; height: 500px;">
                            <i class="fas fa-utensils fa-5x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Product Name -->
                    <h1 class="product-title mb-2 text-uppercase fw-bold"><?= htmlspecialchars($product['name']) ?></h1>

                    <?php 
                        $avgRating = $product['avg_rating'] ?? 0;
                        $totalReviews = $product['total_reviews'] ?? 0;
                    ?>
                    <div class="d-flex flex-wrap align-items-center mb-3 small text-muted">
                        <span class="me-3">SKU: <?= htmlspecialchars($product['id']) ?></span>
                        <div class="d-flex align-items-center">
                            <div class="me-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= round($avgRating) ? 'text-warning' : 'text-muted' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="ms-1">(<?= (int)$totalReviews ?> đánh giá)</span>
                        </div>
                    </div>

                    <!-- Category, status & code -->
                    <div class="mb-3 d-flex flex-wrap align-items-center gap-2">
                        <span class="badge bg-primary fs-6">
                            <i class="fas fa-tag me-1"></i>
                            <?= htmlspecialchars($product['category_name']) ?>
                        </span>
                        <?php 
                        $isAvailable = isset($product['is_available']) && $product['is_available'] == 1;
                        $isStopped = isset($product['is_available']) && $product['is_available'] == 2;
                        ?>
                        <?php if ($isStopped): ?>
                            <span class="badge bg-warning fs-6">
                                <i class="fas fa-ban me-1"></i>Ngừng bán
                            </span>
                        <?php elseif ($isAvailable): ?>
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>Còn hàng
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-times-circle me-1"></i>Hết hàng
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Price -->
                    <div class="price-section mb-3">
                        <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                            <div class="d-flex align-items-center flex-wrap gap-3">
                                <span class="sale-price text-danger fs-2 fw-bold"><?= number_format($product['sale_price']) ?>đ</span>
                                <span class="original-price text-muted fs-4 text-decoration-line-through"><?= number_format($product['price']) ?>đ</span>
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="fas fa-percent me-1"></i>
                                    <?= round((($product['price'] - $product['sale_price']) / $product['price']) * 100) ?>% OFF
                                </span>
                            </div>
                        <?php else: ?>
                            <span class="current-price text-dark fs-2 fw-bold"><?= number_format($product['price']) ?>đ</span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <div class="fw-semibold mb-2">Màu sắc:</div>
                        <?php if (!empty($colors)): ?>
                            <div class="color-selector d-flex flex-wrap gap-2">
                                <?php foreach ($colors as $color): ?>
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-sm rounded-0 px-3 btn-color-option"
                                            onclick="selectColor(this, '<?= htmlspecialchars($color['name']) ?>')"
                                            data-color="<?= htmlspecialchars($color['name']) ?>">
                                        <?= htmlspecialchars($color['name']) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">Không có màu sắc</span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <div class="fw-semibold mb-2">Size:</div>
                        <?php if (!empty($sizes)): ?>
                            <div class="size-selector d-flex flex-wrap gap-2">
                                <?php foreach ($sizes as $size): ?>
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-sm rounded-0 px-3 btn-size-option"
                                            onclick="selectSize(this, '<?= htmlspecialchars($size['name']) ?>')"
                                            data-size="<?= htmlspecialchars($size['name']) ?>">
                                        <?= htmlspecialchars($size['name']) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">Không có size</span>
                            <!-- Debug: Hiển thị số lượng size -->
                            <small class="d-block text-muted mt-1">
                                (Debug: Số size = <?= count($sizes ?? []) ?>)
                            </small>
                        <?php endif; ?>
                    </div>

                    <!-- Quantity and action buttons -->
                    <?php if ($isStopped): ?>
                        <div class="alert alert-danger mb-4">
                            <i class="fas fa-ban me-2"></i>
                            <strong>Sản phẩm đã ngừng bán</strong>
                            <p class="mb-0 mt-2">Sản phẩm này hiện không còn được bán. Vui lòng chọn sản phẩm khác.</p>
                        </div>
                    <?php elseif ($isAvailable): ?>
                        <div class="add-to-cart-section mb-4">
                            <div class="mb-3">
                                <label for="quantity" class="form-label fw-semibold">Số lượng</label>
                                <div class="input-group" style="width: 150px;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" class="form-control text-center" 
                                           value="1" min="1" max="5" onchange="checkQuantity()">
                                    <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-2">Tối đa mua 5 sản phẩm. Nếu cần mua nhiều hơn, vui lòng <a href="/contact">liên hệ người bán</a></small>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-primary btn-lg flex-fill text-uppercase" type="button" onclick="addToCartDetail(<?= $product['id'] ?>)">
                                    Thêm vào giỏ
                                </button>
                                <button class="btn btn-outline-primary btn-lg flex-fill text-uppercase" type="button" onclick="buyNow(<?= $product['id'] ?>)">
                                    Mua hàng
                                </button>
                
                            </div>
                        </div>

                        <!-- Description tabs -->
                        <div class="product-description-tabs mt-3">
                            <ul class="nav border-0 mb-3">
                                <li class="nav-item me-3">
                                    <button type="button" class="nav-link px-0 py-1 text-uppercase desc-tab-link active" data-tab="intro" onclick="selectDescTab('intro')">
                                        Giới thiệu
                                    </button>
                                </li>
                                <li class="nav-item me-3">
                                    <button type="button" class="nav-link px-0 py-1 text-uppercase desc-tab-link" data-tab="detail" onclick="selectDescTab('detail')">
                                        Chi tiết sản phẩm
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link px-0 py-1 text-uppercase desc-tab-link" data-tab="review" onclick="selectDescTab('review')">
                                        Đánh giá
                                    </button>
                                </li>
                            </ul>
                            <div class="pt-2">
                                <div class="desc-tab-pane" data-tab="intro">
                                    <p class="text-muted lh-lg mb-0">
                                        <?= nl2br(htmlspecialchars($product['intro'] ?? '')) ?>
                                    </p>
                                </div>
                                <div class="desc-tab-pane d-none" data-tab="detail">
                                    <p class="text-muted lh-lg mb-0">
                                        <?= nl2br(htmlspecialchars($product['detail'] ?? '')) ?>
                                    </p>
                                </div>
                                <div class="desc-tab-pane d-none" data-tab="review">
                                    <?php if (empty($reviews)): ?>
                                        <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
                                    <?php else: ?>
                                        <?php foreach ($reviews as $review): ?>
                                            <div class="mb-3 pb-3 border-bottom review-item" data-review-id="<?= $review['id'] ?>">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <strong><?= htmlspecialchars($review['user_name']) ?></strong>
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
                                                <div class="mb-1 review-rating-display">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="review-comment-display">
                                                    <?php if (!empty($review['comment'])): ?>
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
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <hr>

                                    <?php if (!empty($_SESSION['user_id'])): ?>
                                        <?php if (isset($hasPurchased) && $hasPurchased): ?>
                                            <h6 class="mb-2">Viết đánh giá của bạn</h6>
                                            <form id="review-form" onsubmit="event.preventDefault(); submitReview(<?= (int)$product['id'] ?>);">
                                                <div class="mb-2">
                                                    <label class="form-label mb-1">Chọn số sao:</label>
                                                    <div id="review-stars" class="text-warning fs-5">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="far fa-star review-star" data-value="<?= $i ?>" onclick="setReviewRating(<?= $i ?>)"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <input type="hidden" id="review-rating" value="0">
                                                </div>
                                                <div class="mb-2">
                                                    <label for="review-comment" class="form-label mb-1">Nhận xét của bạn</label>
                                                    <textarea id="review-comment" class="form-control" rows="3" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm mt-1">Gửi đánh giá</button>
                                                <small id="review-message" class="d-block mt-1"></small>
                                            </form>
                                        <?php else: ?>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Bạn chỉ có thể đánh giá sản phẩm đã mua.</strong>
                                                <p class="mb-0 mt-2">Vui lòng mua sản phẩm này trước khi đánh giá.</p>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="text-muted mb-0">
                                            Vui lòng <a href="/login">đăng nhập</a> để viết đánh giá.
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Sản phẩm này hiện tại đã hết hàng. Vui lòng quay lại sau!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products Section -->
<?php if (!empty($relatedProducts)): ?>
<section class="py-5 bg-white related-products">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title text-uppercase">
                    <i class="fas fa-shirt me-2"></i>
                    Sản phẩm tương tự
                </h2>
                <p class="text-muted">Những mẫu tương tự bạn có thể quan tâm</p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($relatedProducts as $relatedProduct): ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="product-card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                        <div class="position-relative">
                            <a href="/product/<?= $relatedProduct['id'] ?>" class="text-decoration-none">
                                <?php 
                                    $relatedImg = ImageHelper::getImageSrc($relatedProduct['image_url'] ?? null);
                                ?>
                                <?php if (!empty($relatedImg)): ?>
                                    <img src="<?= htmlspecialchars($relatedImg) ?>" 
                                         class="card-img-top" alt="<?= htmlspecialchars($relatedProduct['name']) ?>" 
                                         style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-shirt fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </a>
                            
                            <?php if (!empty($relatedProduct['sale_price']) && $relatedProduct['sale_price'] < $relatedProduct['price']): ?>
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-warning">
                                        <i class="fas fa-percent me-1"></i>Sale
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($relatedProduct['is_available']) && $relatedProduct['is_available'] == 2): ?>
                                <div class="position-absolute top-0 <?= (!empty($relatedProduct['sale_price']) && $relatedProduct['sale_price'] < $relatedProduct['price']) ? 'end-0' : 'start-0' ?> m-2">
                                    <span class="badge bg-danger">
                                        <i class="fas fa-ban me-1"></i>Ngừng bán
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/product/<?= $relatedProduct['id'] ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($relatedProduct['name']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small"><?= htmlspecialchars(substr($relatedProduct['description'], 0, 80)) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="price">
                                    <?php if (!empty($relatedProduct['sale_price']) && $relatedProduct['sale_price'] < $relatedProduct['price']): ?>
                                        <span class="text-danger fw-bold"><?= number_format($relatedProduct['sale_price']) ?>đ</span>
                                        <small class="text-muted text-decoration-line-through"><?= number_format($relatedProduct['price']) ?>đ</small>
                                    <?php else: ?>
                                        <span class="text-primary fw-bold"><?= number_format($relatedProduct['price']) ?>đ</span>
                                    <?php endif; ?>
                                </div>
                                <?php 
                                $relatedIsAvailable = isset($relatedProduct['is_available']) && $relatedProduct['is_available'] == 1;
                                $relatedIsStopped = isset($relatedProduct['is_available']) && $relatedProduct['is_available'] == 2;
                                ?>
                                <?php if ($relatedIsStopped): ?>
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="fas fa-ban me-1"></i>Ngừng bán
                                    </button>
                                <?php elseif ($relatedIsAvailable): ?>
                                    <button class="btn btn-primary btn-sm" onclick="addToCart(<?= $relatedProduct['id'] ?>)">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        Hết hàng
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
.product-title {
    color: #8b0000;
}

.product-description-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    border-radius: 0;
    color: #580000de;
    font-weight: 500;
    transition: color 0.2s ease, border-color 0.2s ease;
}

.product-description-tabs .nav-link.active {
    border-bottom-color: #8b0000;
    color: #8b0000;
}

.product-description-tabs .nav-link:hover {
    color: #8b0000;
}

.btn-color-option.active,
.btn-size-option.active {
    border-color: #8b0000;
    box-shadow: 0 0 0 2px rgba(139, 0, 0, 0.15);
    background-color: #fff;
    color: #000;
}

/* Quantity +/- icons use brand yellow */
.add-to-cart-section .btn-outline-secondary i {
    color: #000;
}

/* Quantity +/- button hover uses brand yellow */
.add-to-cart-section .btn-outline-secondary:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

/* Make description text bold and black */
.desc-tab-pane p {
    font-weight: 450 !important;
    color: #000 !important;
}

/* Related Products Section - Brand Colors */
.section-title {
    color: #8b0000 !important;
}

.section-title i {
    color: #ffc107;
}

.related-products .product-card .card-title a {
    color: #8b0000 !important;
}

.related-products .product-card .card-title a:hover {
    color: #ffc107 !important;
}

.related-products .price .text-primary {
    color: #8b0000 !important;
}

.related-products .btn-primary {
    background-color: #8b0000 !important;
    border-color: #8b0000 !important;
}

.related-products .btn-primary:hover {
    background-color: #5a0000 !important;
    border-color: #5a0000 !important;
}

.related-products .badge.bg-warning {
    background-color: #ffc107 !important;
    color: #000 !important;
}
</style>

<script>
// Quantity controls
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue < 10) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

// Change main image
function changeMainImage(src, thumbnail) {
    const mainImg = document.getElementById('main-image');
    if (mainImg) {
        mainImg.src = src;
        // Update thumbnail borders
        document.querySelectorAll('.img-thumbnail').forEach(thumb => {
            thumb.classList.remove('border-primary');
        });
        thumbnail.classList.add('border-primary');
    }
}

// Color selection
function selectColor(button, colorName) {
    // Remove active state from all color buttons
    document.querySelectorAll('.btn-color-option').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active state to clicked button
    button.classList.add('active');
    
    // Update selected color display
    const selectedColorDisplay = document.getElementById('selected-color-display');
    if (selectedColorDisplay) {
        selectedColorDisplay.textContent = colorName;
    }
}

// Size selection
function selectSize(button, sizeName) {
    // Remove active state from all size buttons
    document.querySelectorAll('.btn-size-option').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active state to clicked button
    button.classList.add('active');
    
    // Update selected size display
    const selectedSizeDisplay = document.getElementById('selected-size-display');
    if (selectedSizeDisplay) {
        selectedSizeDisplay.textContent = sizeName;
    }
}

// Auto-select first color and size on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-select first color if none selected
    const firstColor = document.querySelector('.btn-color-option');
    if (firstColor && !document.querySelector('.btn-color-option.active')) {
        selectColor(firstColor, firstColor.dataset.color);
    }
    
    // Auto-select first size if none selected
    const firstSize = document.querySelector('.btn-size-option');
    if (firstSize && !document.querySelector('.btn-size-option.active')) {
        selectSize(firstSize, firstSize.dataset.size);
    }
});

// Description tabs
function selectDescTab(tab) {
    const links = document.querySelectorAll('.desc-tab-link');
    const panes = document.querySelectorAll('.desc-tab-pane');

    links.forEach(function (link) {
        if (link.dataset.tab === tab) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    panes.forEach(function (pane) {
        if (pane.dataset.tab === tab) {
            pane.classList.remove('d-none');
        } else {
            pane.classList.add('d-none');
        }
    });
}

// Review rating selection
function setReviewRating(value) {
    const stars = document.querySelectorAll('#review-stars .review-star');
    stars.forEach(star => {
        const starValue = parseInt(star.getAttribute('data-value'));
        if (starValue <= value) {
            star.classList.remove('far');
            star.classList.add('fas');
        } else {
            star.classList.remove('fas');
            star.classList.add('far');
        }
    });

    const ratingInput = document.getElementById('review-rating');
    if (ratingInput) {
        ratingInput.value = value;
    }
}

// Submit review via AJAX
function submitReview(productId) {
    const ratingInput = document.getElementById('review-rating');
    const commentInput = document.getElementById('review-comment');
    const messageEl = document.getElementById('review-message');

    if (!ratingInput || !commentInput || !messageEl) return;

    const rating = parseInt(ratingInput.value) || 0;
    const comment = commentInput.value.trim();

    if (!rating || rating < 1 || rating > 5) {
        messageEl.textContent = 'Vui lòng chọn số sao (1-5).';
        messageEl.className = 'd-block mt-1 text-danger';
        return;
    }

    const formData = new URLSearchParams();
    formData.append('product_id', productId);
    formData.append('rating', rating);
    formData.append('comment', comment);

    fetch('/add-review', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageEl.textContent = data.message || 'Đánh giá thành công!';
            messageEl.className = 'd-block mt-1 text-success';
            // Reload page to see new review and updated average rating
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            messageEl.textContent = data.message || 'Có lỗi xảy ra, vui lòng thử lại!';
            messageEl.className = 'd-block mt-1 text-danger';
        }
    })
    .catch(() => {
        messageEl.textContent = 'Có lỗi xảy ra, vui lòng thử lại!';
        messageEl.className = 'd-block mt-1 text-danger';
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
            // Reload page to see updated review
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
            // Reload page to see updated reviews
            window.location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
        }
    })
    .catch(() => {
        alert('Có lỗi xảy ra, vui lòng thử lại!');
    });
}

// Buy now: add to cart then redirect to checkout
function buyNow(productId) {
    const quantity = parseInt(document.getElementById('quantity').value) || 1;
    
    // Get selected color and size (or default to first available)
    const selectedColor = document.querySelector('.btn-color-option.active');
    const selectedSize = document.querySelector('.btn-size-option.active');
    
    let colorName = '';
    let sizeName = '';
    
    // If no color selected, try to get first available color
    if (selectedColor) {
        colorName = selectedColor.dataset.color || '';
    } else {
        const firstColor = document.querySelector('.btn-color-option');
        if (firstColor) {
            colorName = firstColor.dataset.color || '';
        }
    }
    
    // If no size selected, try to get first available size
    if (selectedSize) {
        sizeName = selectedSize.dataset.size || '';
    } else {
        const firstSize = document.querySelector('.btn-size-option');
        if (firstSize) {
            sizeName = firstSize.dataset.size || '';
        }
    }
    
    // Validation: Require color and size if available
    const hasColors = document.querySelectorAll('.btn-color-option').length > 0;
    const hasSizes = document.querySelectorAll('.btn-size-option').length > 0;
    
    if (hasColors && !colorName) {
        alert('Vui lòng chọn màu sắc!');
        return;
    }
    
    if (hasSizes && !sizeName) {
        alert('Vui lòng chọn size!');
        return;
    }

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity,
            color: colorName,
            size: sizeName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/checkout';
        } else {
            const toast = document.getElementById('cartToast');
            const toastHeader = toast.querySelector('.toast-header');
            const toastBody = toast.querySelector('.toast-body');

            toastHeader.className = 'toast-header bg-danger text-white';
            toastHeader.querySelector('strong').textContent = 'Lỗi';
            toastHeader.querySelector('i').className = 'fas fa-exclamation-circle me-2';
            toastBody.textContent = data.message || 'Có lỗi xảy ra, vui lòng thử lại!';

            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 4000
            });
            bsToast.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Add to cart with quantity
function addToCartDetail(productId) {
    console.log('addToCartDetail called with productId:', productId);
    
    const quantity = parseInt(document.getElementById('quantity').value);
    console.log('Quantity:', quantity);
    
    // Get selected color and size
    const selectedColor = document.querySelector('.btn-color-option.active');
    const selectedSize = document.querySelector('.btn-size-option.active');
    
    const colorName = selectedColor ? selectedColor.dataset.color : '';
    const sizeName = selectedSize ? selectedSize.dataset.size : '';
    
    console.log('Selected elements:', { selectedColor, selectedSize });
    console.log('Color and size:', { colorName, sizeName });
    
    // Validation: Require color and size if available
    const hasColors = document.querySelectorAll('.btn-color-option').length > 0;
    const hasSizes = document.querySelectorAll('.btn-size-option').length > 0;
    
    console.log('Has colors/sizes:', { hasColors, hasSizes });
    
    if (hasColors && !selectedColor) {
        alert('Vui lòng chọn màu sắc!');
        return;
    }
    
    if (hasSizes && !selectedSize) {
        alert('Vui lòng chọn size!');
        return;
    }
    
    // Debug: Log ra console
    console.log('Adding to cart:', {
        productId: productId,
        quantity: quantity,
        color: colorName,
        size: sizeName,
        selectedColor: selectedColor,
        selectedSize: selectedSize
    });
    
    const requestData = {
        product_id: productId,
        quantity: quantity,
        color: colorName,
        size: sizeName
    };
    
    console.log('Request data:', requestData);
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            document.getElementById('cart-count').textContent = data.cartCount;
            
            // Show success toast
            const toast = document.getElementById('cartToast');
            const toastBody = toast.querySelector('.toast-body');
            toastBody.textContent = `Đã thêm ${quantity} sản phẩm vào giỏ hàng!`;
            
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 1500
            });
            bsToast.show();
        } else {
            // Show error toast
            const toast = document.getElementById('cartToast');
            const toastHeader = toast.querySelector('.toast-header');
            const toastBody = toast.querySelector('.toast-body');
            
            toastHeader.className = 'toast-header bg-danger text-white';
            toastHeader.querySelector('strong').textContent = 'Lỗi';
            toastHeader.querySelector('i').className = 'fas fa-exclamation-circle me-2';
            toastBody.textContent = data.message || 'Có lỗi xảy ra, vui lòng thử lại!';
            
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 4000
            });
            bsToast.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Show error toast
        const toast = document.getElementById('cartToast');
        const toastHeader = toast.querySelector('.toast-header');
        const toastBody = toast.querySelector('.toast-body');
        
        toastHeader.className = 'toast-header bg-danger text-white';
        toastHeader.querySelector('strong').textContent = 'Lỗi';
        toastHeader.querySelector('i').className = 'fas fa-exclamation-circle me-2';
        toastBody.textContent = 'Có lỗi xảy ra, vui lòng thử lại!';
        
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 4000
        });
        bsToast.show();
    });
}

// Regular add to cart for related products
function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            document.getElementById('cart-count').textContent = data.cartCount;
            
            // Show success toast
            const toast = document.getElementById('cartToast');
            const toastBody = toast.querySelector('.toast-body');
            toastBody.textContent = 'Đã thêm sản phẩm vào giỏ hàng!';
            
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 1500
            });
            bsToast.show();
        } else {
            // Show error toast
            const toast = document.getElementById('cartToast');
            const toastHeader = toast.querySelector('.toast-header');
            const toastBody = toast.querySelector('.toast-body');
            
            toastHeader.className = 'toast-header bg-danger text-white';
            toastHeader.querySelector('strong').textContent = 'Lỗi';
            toastHeader.querySelector('i').className = 'fas fa-exclamation-circle me-2';
            toastBody.textContent = data.message || 'Có lỗi xảy ra, vui lòng thử lại!';
            
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 4000
            });
            bsToast.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Show error toast
        const toast = document.getElementById('cartToast');
        const toastHeader = toast.querySelector('.toast-header');
        const toastBody = toast.querySelector('.toast-body');
        
        toastHeader.className = 'toast-header bg-danger text-white';
        toastHeader.querySelector('strong').textContent = 'Lỗi';
        toastHeader.querySelector('i').className = 'fas fa-exclamation-circle me-2';
        toastBody.textContent = 'Có lỗi xảy ra, vui lòng thử lại!';
        
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 4000
        });
        bsToast.show();
    });
}

// Quantity control functions
function increaseQuantity() {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) || 1;
    if (value < 5) {
        input.value = value + 1;
    } else {
        alert('Tối đa chỉ có thể mua 5 sản phẩm. Nếu cần mua nhiều hơn, vui lòng liên hệ người bán.');
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) || 1;
    if (value > 1) {
        input.value = value - 1;
    }
}

function checkQuantity() {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) || 1;
    
    if (value > 5) {
        input.value = 5;
        alert('Tối đa chỉ có thể mua 5 sản phẩm. Nếu cần mua nhiều hơn, vui lòng liên hệ người bán.');
    } else if (value < 1) {
        input.value = 1;
    }
}
</script>
