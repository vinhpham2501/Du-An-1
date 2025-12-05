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
            'kicker' => 'Hồn Việt trong từng đường kim mũi chỉ',
            'title' => 'Trang Phục Truyền Thống',
            'subtitle' => 'Bộ sưu tập tôn vinh nét đẹp văn hoá và lịch sử Việt',
            'button_text' => 'Mua sắm ngay',
            'button_link' => '/products',
        ],
        [
            'image' => $secondBannerImageUrl ?? 'https://images.pexels.com/photos/30466704/pexels-photo-30466704.jpeg',
            'kicker' => 'Thanh tao – trang nhã – đậm chất cố đô',
            'title' => 'Áo Nhật Bình Cổ Truyền',
            'subtitle' => 'Hơi thở cung đình xưa trong từng họa tiết thêu tay',
            'button_text' => 'Xem bộ sưu tập',
            'button_link' => '/products',
        ],
        [
            'image' => $thirdBannerImageUrl ?? 'https://images.pexels.com/photos/34889454/pexels-photo-34889454.jpeg',
            'kicker' => 'Giữ gìn nét đẹp Việt qua năm tháng',
            'title' => 'Áo Tấc Truyền Thống',
            'subtitle' => 'Biểu tượng văn hoá thanh lịch của người Việt xưa',
            'button_text' => 'Khám phá ngay',
            'button_link' => '/products',
        ],
        [
            'image' => $thirdBannerImageUrl ?? 'https://i.pinimg.com/1200x/80/99/d7/8099d72d4e89dc5992973af924476f21.jpg',
            'kicker' => 'Biểu tượng bất hủ của người Việt',
            'title' => 'Nón Lá Truyền Thống',
            'subtitle' => 'inh tế trên từng thớ lá – từ làng nghề đến tay bạn',
            'button_text' => 'Khám phá ngay',
            'button_link' => '/products',
        ],
    ];
?>

<section class="main-hero">
    <div id="mainHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
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
                        <div class="featured-card">
                            <div class="featured-card-image-wrapper">
                                <?php $img = ImageHelper::getImageSrc($product['image_url'] ?? null); ?>
                                <?php if (!empty($img)): ?>
                                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="featured-card-image" loading="lazy" decoding="async">
                                <?php else: ?>
                                    <div class="featured-card-image placeholder d-flex align-items-center justify-content-center">
                                        <i class="fas fa-shirt fa-3x text-muted"></i>
                                    </div>
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
                <p class="featured-kicker mb-1">Phụ kiện nổi bật</p>
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
                $heritageVideoUrl = $heritageVideoUrl ?? 'https://www.youtube.com/embed/J0K1MpmZp5E?si=_Wnh4KwhRwXbdN3Z';
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
