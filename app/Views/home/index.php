<?php use App\Helpers\ImageHelper; $title = 'Trang chủ - Sắc Việt Traditional Shop'; ?>

<!-- Include Homepage CSS -->
<link rel="stylesheet" href="/css/homepage.css">

<!-- Include Homepage JavaScript -->
<script src="/js/homepage.js"></script>

<?php // removed duplicate CSS include to avoid double-loading styles ?>

<!-- Main Hero Carousel -->
<?php 
    // Cấu hình các slide hero: bạn chỉ cần thay link ảnh và nội dung bên dưới
    $heroSlides = [
        [
            'image' => $bannerImageUrl ?? 'https://images.pexels.com/photos/32755016/pexels-photo-32755016.jpeg',
            'kicker' => 'Tinh hoa Việt trên từng thước vải',
            'title' => 'Gốm Sứ Bát Tràng',
            'subtitle' => 'Bộ sưu tập mang đậm hơi thở làng nghề truyền thống',
            'button_text' => 'Mua sắm ngay',
            'button_link' => '/products',
        ],
        [
            'image' => $secondBannerImageUrl ?? 'https://images.pexels.com/photos/30466704/pexels-photo-30466704.jpeg',
            'kicker' => 'Nghệ thuật thủ công truyền thống',
            'title' => 'Tuyệt Tác Sơn Mài',
            'subtitle' => 'Tôn vinh vẻ đẹp Việt qua từng đường nét tinh xảo',
            'button_text' => 'Xem bộ sưu tập',
            'button_link' => '/products',
        ],
        [
            'image' => $thirdBannerImageUrl ?? 'https://images.pexels.com/photos/34889454/pexels-photo-34889454.jpeg',
            'kicker' => 'Sắc Việt trong từng trang phục',
            'title' => 'Áo Dài Truyền Thống',
            'subtitle' => 'Kết hợp tinh hoa cổ điển và hơi thở hiện đại',
            'button_text' => 'Khám phá ngay',
            'button_link' => '/products',
        ],
        [
            'image' => $thirdBannerImageUrl ?? 'https://images.pexels.com/photos/33409159/pexels-photo-33409159.jpeg',
            'kicker' => 'Sắc Việt trong từng trang phục',
            'title' => 'Áo Dài Truyền Thống',
            'subtitle' => 'Kết hợp tinh hoa cổ điển và hơi thở hiện đại',
            'button_text' => 'Khám phá ngay',
            'button_link' => '/products',
        ],
    ];
?>

<section class="main-hero">
    <div id="mainHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-indicators">
            <?php foreach ($heroSlides as $index => $_slide): ?>
                <button type="button" data-bs-target="#mainHeroCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner">
            <?php foreach ($heroSlides as $index => $slide): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="main-hero-slide" style="background-image: url('<?= htmlspecialchars($slide['image']) ?>');">
                        <div class="main-hero-overlay"></div>
                        <div class="container h-100">
                            <div class="row h-100 align-items-center">
                                <div class="col-lg-7 col-md-9">
                                    <div class="main-hero-caption text-white">
                                        <p class="hero-kicker mb-3"><?= htmlspecialchars($slide['kicker']) ?></p>
                                        <h1 class="hero-title mb-3"><?= htmlspecialchars($slide['title']) ?></h1>
                                        <p class="hero-subtitle mb-4"><?= htmlspecialchars($slide['subtitle']) ?></p>
                                        <a href="<?= htmlspecialchars($slide['button_link']) ?>" class="btn btn-primary btn-lg px-4">
                                            <?= htmlspecialchars($slide['button_text']) ?>
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#mainHeroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainHeroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<!-- Featured Collection Section -->
<?php if (!empty($featuredProducts)): ?>
<section class="featured-collection-section py-5">
    <div class="container">
        <div class="row align-items-end mb-4">
            <div class="col-md-8">
                <p class="featured-kicker mb-1">Sản Phẩm nổi bật</p>
                <h2 class="featured-title mb-0">Tinh Hoa Nghệ Thuật</h2>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="/products" class="featured-view-all-link">
                    Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <?php
            // Ưu tiên lấy 4 sản phẩm nổi bật theo tên cụ thể người dùng muốn
            $preferredNames = [
                'áo giao lĩnh quấn thường',
                'áo ngũ thân nam hoài cổ',
                'áo tấc sa xước nữ',
                'nhật bình lan hồ điệp',
            ];

            // Nguồn dữ liệu ưu tiên: danh sách products đang hiển thị trên home, sau đó mới tới featuredProducts
            $baseList = $products ?? [];
            if (empty($baseList) && !empty($featuredProducts)) {
                $baseList = $featuredProducts;
            }

            $ordered = [];
            $remaining = $baseList;

            foreach ($preferredNames as $name) {
                foreach ($remaining as $key => $p) {
                    $productName = mb_strtolower($p['name'] ?? '', 'UTF-8');
                    if (strpos($productName, $name) !== false) {
                        $ordered[] = $p;
                        unset($remaining[$key]);
                        break;
                    }
                }
            }

            // Nếu chưa đủ 4 sản phẩm thì lấy bù từ phần còn lại
            $featuredSource = array_values(array_merge($ordered, $remaining));
        ?>

        <div class="row g-4">
            <?php $highlightIndex = 1; ?>
            <?php foreach (array_slice($featuredSource, 0, 4) as $index => $product): ?>
                <div class="col-6 col-md-3">
                    <a href="/product/<?= $product['id'] ?>" class="featured-card-link text-decoration-none text-dark">
                        <div class="featured-card <?= $index === $highlightIndex ? 'featured-card--highlight' : '' ?>">
                            <div class="featured-card-image-wrapper">
                                <?php $img = ImageHelper::getImageSrc($product['image_url'] ?? null); ?>
                                <?php if (!empty($img)): ?>
                                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="featured-card-image" loading="lazy" decoding="async">
                                <?php else: ?>
                                    <div class="featured-card-image placeholder d-flex align-items-center justify-content-center">
                                        <i class="fas fa-shirt fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>

                                <?php if ($index === $highlightIndex): ?>
                                    <span class="featured-badge">Khải phá</span>
                                <?php endif; ?>
                            </div>
                            <div class="featured-card-body text-center mt-3">
                                <h5 class="mb-1"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="text-primary mb-0 fw-bold">
                                    <?= number_format($product['price']) ?>đ
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Ao Dai Story Section -->
<section class="ao-dai-story-section py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="ao-dai-story-image-wrapper">
                    <?php
                        $aoDaiStoryImage = $aoDaiStoryImage ?? 'https://images.pexels.com/photos/34889454/pexels-photo-34889454.jpeg';
                    ?>
                    <div class="ao-dai-story-image"
                         style="background-image: url('<?= htmlspecialchars($aoDaiStoryImage) ?>');">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ao-dai-story-content text-white">
                    <p class="story-kicker mb-2">Bộ Sưu Tập Áo Dài Việt</p>
                    <h2 class="story-title mb-2">Áo Dài</h2>
                    <h3 class="story-subtitle mb-4">Vẻ Đẹp Thuần Việt</h3>
                    <p class="story-text mb-4">
                        Lấy cảm hứng từ hình ảnh người phụ nữ Việt Nam dịu dàng, áo dài là sự kết hợp tinh tế giữa đường nét truyền thống và form dáng hiện đại. 
                        Từng đường may, tà áo, cổ tay đều được chăm chút để tôn lên vẻ đẹp thanh lịch, kín đáo nhưng vẫn vô cùng quyến rũ.
                    </p>
                    <p class="story-text mb-4">
                        Tại Sắc Việt, mỗi thiết kế áo dài là một câu chuyện về chất liệu, hoa văn và văn hoá – để khi khoác lên mình, bạn không chỉ mặc một bộ trang phục, 
                        mà còn mang theo niềm tự hào về bản sắc Việt.
                    </p>
                    <a href="/products" class="btn btn-outline-light px-4">
                        Xem Bộ Sưu Tập
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Accessories Featured Section -->
<?php
    // Lấy danh sách phụ kiện từ products theo tên danh mục chứa 'phụ kiện'
    $accessoryProducts = [];
    if (!empty($products)) {
        foreach ($products as $p) {
            $catName = mb_strtolower($p['category_name'] ?? '', 'UTF-8');
            if (strpos($catName, 'phụ kiện') !== false) {
                $accessoryProducts[] = $p;
            }
        }
    }

    // Nếu chưa có dữ liệu từ products thì fallback sang featuredProducts
    if (empty($accessoryProducts) && !empty($featuredProducts)) {
        foreach ($featuredProducts as $p) {
            $catName = mb_strtolower($p['category_name'] ?? '', 'UTF-8');
            if (strpos($catName, 'phụ kiện') !== false) {
                $accessoryProducts[] = $p;
            }
        }
    }
?>

<?php if (!empty($accessoryProducts)): ?>
<section class="accessory-featured-section py-5">
    <div class="container">
        <div class="row align-items-end mb-4">
            <div class="col-md-12">
                <p class="featured-kicker mb-1">Sản phẩm nổi bật</p>
                <h2 class="featured-title mb-0">Tuyệt Tác Thủ Công</h2>
            </div>
        </div>

        <?php
            $accessoryPrimary = $accessoryProducts[0];
            $accessoryOthers = array_slice($accessoryProducts, 1, 4);
        ?>

        <div class="row g-4 accessory-grid">
            <div class="col-lg-7">
                <a href="/product/<?= $accessoryPrimary['id'] ?>" class="accessory-card-link text-decoration-none text-white">
                    <div class="accessory-card accessory-card--primary">
                        <?php $img = ImageHelper::getImageSrc($accessoryPrimary['image_url'] ?? null); ?>
                        <?php if (!empty($img)): ?>
                            <div class="accessory-card-image" style="background-image: url('<?= htmlspecialchars($img) ?>');"></div>
                        <?php else: ?>
                            <div class="accessory-card-image accessory-placeholder d-flex align-items-center justify-content-center">
                                <i class="fas fa-gem fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="accessory-card-overlay"></div>
                        <div class="accessory-card-body">
                            <h5 class="mb-1"><?= htmlspecialchars($accessoryPrimary['name']) ?></h5>
                            <p class="mb-1 accessory-price"><?= number_format($accessoryPrimary['price']) ?>đ</p>
                            <span class="btn btn-light btn-sm rounded-pill px-3">Xem chi tiết</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-5">
                <div class="row g-3">
                    <?php foreach ($accessoryOthers as $p): ?>
                        <div class="col-sm-6">
                            <a href="/product/<?= $p['id'] ?>" class="accessory-card-link text-decoration-none text-white">
                                <div class="accessory-card accessory-card--small">
                                    <?php $img = ImageHelper::getImageSrc($p['image_url'] ?? null); ?>
                                    <?php if (!empty($img)): ?>
                                        <div class="accessory-card-image" style="background-image: url('<?= htmlspecialchars($img) ?>');"></div>
                                    <?php else: ?>
                                        <div class="accessory-card-image accessory-placeholder d-flex align-items-center justify-content-center">
                                            <i class="fas fa-gem fa-2x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="accessory-card-overlay"></div>
                                    <div class="accessory-card-body accessory-card-body--small">
                                        <h6 class="mb-1"><?= htmlspecialchars($p['name']) ?></h6>
                                        <p class="mb-0 accessory-price small"><?= number_format($p['price']) ?>đ</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="/products?category_id=<?= htmlspecialchars($accessoryProducts[0]['category_id']) ?>" class="btn btn-outline-dark px-4 rounded-pill">
                Xem tất cả phụ kiện
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Heritage Video Section (Full Screen) -->
<section class="heritage-video-section">
    <div class="heritage-video-wrapper">
        <div class="ratio ratio-16x9 heritage-video-frame">
            <?php
                // TODO: Bạn dán mã nhúng (iframe) hoặc sửa src video ở đây
                $heritageVideoUrl = $heritageVideoUrl ?? 'https://www.youtube.com/embed/0GGvA32Kzhw?si=bjKSvx1LNlOsM-rC';
            ?>
            <iframe
                src="<?= htmlspecialchars($heritageVideoUrl) ?>"
                title="Video giới thiệu Sắc Việt"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
                loading="lazy"></iframe>
        </div>
    </div>
</section>

<!-- Features Icons Row -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="feature">
                    <i class="fas fa-truck fa-2x text-primary mb-3"></i>
                    <h5 class="mb-2">Giao hàng nhanh chóng</h5>
                    <p class="text-muted">Giao hàng tận nơi trong vòng 24 giờ.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="feature">
                    <i class="fas fa-undo-alt fa-2x text-primary mb-3"></i>
                    <h5 class="mb-2">Đổi trả dễ dàng</h5>
                    <p class="text-muted">Đổi trả sản phẩm trong vòng 7 ngày.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature">
                    <i class="fas fa-lock fa-2x text-primary mb-3"></i>
                    <h5 class="mb-2">Thanh toán an toàn</h5>
                    <p class="text-muted">Hệ thống thanh toán an toàn và bảo mật.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- New Products Section -->
<section class="py-5 bg-light" style="display: none;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">
                    <i class="fas fa-star text-success me-2"></i>
                    Bộ sưu tập mới
                </h2>
                <p class="text-muted">Những thiết kế truyền thống mới nhất vừa cập bến</p>
            </div>
        </div>
        
        <div id="newProductsCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php 
                $newChunks = array_chunk($newProducts, 4);
                foreach ($newChunks as $index => $chunk): 
                ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="row">
                            <?php foreach ($chunk as $product): ?>
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <div class="product-card h-100 shadow-sm">
                                        <div class="position-relative">
                                            <a href="/product/<?= $product['id'] ?>" class="text-decoration-none">
                                                <?php 
                                                    $newImg = ImageHelper::getImageSrc($product['image_url'] ?? null);
                                                ?>
                                                <?php if (!empty($newImg)): ?>
                                                    <img src="<?= htmlspecialchars($newImg) ?>" 
                                                         class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" 
                                                         style="height: 200px; object-fit: cover;" loading="lazy" decoding="async">
                                                <?php else: ?>
                                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                         style="height: 200px;">
                                                        <i class="fas fa-shirt fa-3x text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </a>
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-star me-1"></i>Mới
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                            <p class="card-text text-muted small"><?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="price">
                                                    <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                                        <span class="text-danger fw-bold"><?= number_format($product['sale_price']) ?>đ</span>
                                                        <small class="text-muted text-decoration-line-through"><?= number_format($product['price']) ?>đ</small>
                                                    <?php else: ?>
                                                        <span class="text-primary fw-bold"><?= number_format($product['price']) ?>đ</span>
                                                    <?php endif; ?>
                                                </div>
                                                <button class="btn btn-success btn-sm" onclick="addToCart(<?= $product['id'] ?>)">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($newChunks) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#newProductsCarousel" data-bs-slide="prev">
                    <i class="fas fa-chevron-left text-dark"></i>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#newProductsCarousel" data-bs-slide="next">
                    <i class="fas fa-chevron-right text-dark"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Main Products Section -->
<section class="py-5" style="display: none;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">
                    <i class="fas fa-list me-2"></i>
                    Sản phẩm của cửa hàng
                </h2>
                <p class="text-muted">Bộ sưu tập trang phục truyền thống đa dạng</p>
            </div>
        </div>

        <!-- Categories Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center gap-2" id="categoryFilter">
                    <button class="btn btn-outline-primary category-btn active" data-category="">
                        <i class="fas fa-th-large me-1"></i>Tất cả
                    </button>
                    <?php foreach ($categories as $category): ?>
                        <button class="btn btn-outline-primary category-btn" data-category="<?= $category['id'] ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center py-5" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
            <p class="mt-2 text-muted">Đang tải sản phẩm...</p>
        </div>

        <!-- Products Grid -->
        <div class="row" id="productsContainer">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="product-card h-100 shadow-sm">
                            <div class="position-relative">
                                <a href="/product/<?= $product['id'] ?>" class="text-decoration-none">
                                    <?php 
                                        $gridImg = ImageHelper::getImageSrc($product['image_url'] ?? null);
                                    ?>
                                    <?php if (!empty($gridImg)): ?>
                                        <img src="<?= htmlspecialchars($gridImg) ?>" 
                                             class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" 
                                             style="height: 200px; object-fit: cover;" loading="lazy" decoding="async">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-shirt fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>
                                
                                <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-warning">
                                            <i class="fas fa-percent me-1"></i>Sale
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="/product/<?= $product['id'] ?>" class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted small"><?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="price">
                                        <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                            <span class="text-danger fw-bold"><?= number_format($product['sale_price']) ?>đ</span>
                                            <small class="text-muted text-decoration-line-through"><?= number_format($product['price']) ?>đ</small>
                                        <?php else: ?>
                                            <span class="text-primary fw-bold"><?= number_format($product['price']) ?>đ</span>
                                        <?php endif; ?>
                                    </div>
                                    <button class="btn btn-primary btn-sm" onclick="addToCart(<?= $product['id'] ?>)">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-5x text-muted mb-3"></i>
                    <h3 class="text-muted">Không tìm thấy sản phẩm nào</h3>
                    <p class="text-muted">Vui lòng thử tìm kiếm với từ khóa khác</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer">
            <?php if ($pagination['totalPages'] > 1): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <nav aria-label="Phân trang sản phẩm">
                            <ul class="pagination justify-content-center">
                                <?php if ($pagination['hasPrev']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="#" onclick="filterProducts(<?= $pagination['currentPage'] - 1 ?>); return false;">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php 
                                $start = max(1, $pagination['currentPage'] - 2);
                                $end = min($pagination['totalPages'], $pagination['currentPage'] + 2);
                                for ($i = $start; $i <= $end; $i++): 
                                ?>
                                    <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                                        <a class="page-link" href="#" onclick="filterProducts(<?= $i ?>); return false;"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['hasNext']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="#" onclick="filterProducts(<?= $pagination['currentPage'] + 1 ?>); return false;">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
// Global variables
let currentCategory = '';
let currentPage = 1;

// Category filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const categoryButtons = document.querySelectorAll('.category-btn');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get category ID
            currentCategory = this.getAttribute('data-category');
            currentPage = 1; // Reset to first page
            
            // Filter products
            filterProducts(1);
        });
    });
});

// Filter products function
function filterProducts(page = 1) {
    currentPage = page;
    
    // Show loading spinner
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('productsContainer').style.opacity = '0.5';
    
    // Build query parameters
    const params = new URLSearchParams();
    params.append('page', page);
    
    if (currentCategory) {
        params.append('category_id', currentCategory);
    }
    
    // Make AJAX request
    fetch(`/api/products/filter?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update products container
                document.getElementById('productsContainer').innerHTML = data.products;
                
                // Update pagination
                document.getElementById('paginationContainer').innerHTML = data.pagination;
                
                // Scroll to products section
                document.getElementById('productsContainer').scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            } else {
                // Show error message
                document.getElementById('productsContainer').innerHTML = 
                    '<div class="col-12 text-center py-5">' +
                    '<i class="fas fa-exclamation-triangle fa-5x text-danger mb-3"></i>' +
                    '<h3 class="text-danger">Có lỗi xảy ra</h3>' +
                    '<p class="text-muted">' + (data.message || 'Vui lòng thử lại sau') + '</p>' +
                    '</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('productsContainer').innerHTML = 
                '<div class="col-12 text-center py-5">' +
                '<i class="fas fa-exclamation-triangle fa-5x text-danger mb-3"></i>' +
                '<h3 class="text-danger">Có lỗi xảy ra</h3>' +
                '<p class="text-muted">Vui lòng kiểm tra kết nối mạng và thử lại</p>' +
                '</div>';
        })
        .finally(() => {
            // Hide loading spinner
            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('productsContainer').style.opacity = '1';
        });
}

// Add to cart function (existing)
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
</script>
