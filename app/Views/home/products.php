<?php use App\Helpers\ImageHelper; $title = 'Sản phẩm'; ?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item active">Sản phẩm</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Danh mục</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="/products" class="list-group-item list-group-item-action <?= empty($_GET['category_id']) ? 'active' : '' ?>">Tất cả</a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="/products?category_id=<?= $cat['id'] ?>" class="list-group-item list-group-item-action <?= (($_GET['category_id'] ?? null) == $cat['id']) ? 'active' : '' ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Tìm kiếm</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="/products">
                        <?php if (!empty($_GET['category_id'])): ?>
                            <input type="hidden" name="category_id" value="<?= htmlspecialchars($_GET['category_id']) ?>">
                        <?php endif; ?>
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Tìm món..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header"><h6 class="mb-0">Sắp xếp</h6></div>
                <div class="list-group list-group-flush">
                    <?php 
                        $sort = $_GET['sort'] ?? 'newest';
                        $baseParams = $_GET; unset($baseParams['page']);
                        function buildUrl($params) { return '/products' . (empty($params) ? '' : ('?' . http_build_query($params))); }
                    ?>
                    <?php foreach ([
                        'newest' => 'Mới nhất',
                        'oldest' => 'Cũ nhất',
                        'name' => 'Tên (A-Z)',
                        'price_low' => 'Giá tăng dần',
                        'price_high' => 'Giá giảm dần',
                    ] as $key => $label): 
                        $params = array_merge($baseParams, ['sort' => $key]);
                    ?>
                        <a href="<?= buildUrl($params) ?>" class="list-group-item list-group-item-action <?= $sort === $key ? 'active' : '' ?>">
                            <?= $label ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Tất cả sản phẩm</h4>
                <span class="text-muted">Tổng: <?= (int)($pagination['totalProducts'] ?? count($products)) ?></span>
            </div>

            <div class="row">
                <?php if (empty($products)): ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-search fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Không tìm thấy sản phẩm nào</h5>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <?php $img = ImageHelper::getImageSrc($p['image_url'] ?? null); ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <?php if ($img): ?>
                                    <a href="/product/<?= $p['id'] ?>">
                                        <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['name']) ?>" style="height: 200px; object-fit: cover;">
                                    </a>
                                <?php else: ?>
                                    <a href="/product/<?= $p['id'] ?>" class="text-decoration-none">
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-utensils fa-2x text-muted"></i>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title mb-1">
                                        <a href="/product/<?= $p['id'] ?>" class="text-decoration-none"><?= htmlspecialchars($p['name']) ?></a>
                                    </h6>
                                    <p class="text-muted small mb-3"><?= htmlspecialchars(mb_strimwidth($p['description'] ?? '', 0, 80, '...')) ?></p>
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php if (!empty($p['sale_price'])): ?>
                                                <span class="text-danger fw-bold"><?= number_format($p['sale_price']) ?>đ</span>
                                                <small class="text-muted text-decoration-line-through ms-1"><?= number_format($p['price']) ?>đ</small>
                                            <?php else: ?>
                                                <span class="text-primary fw-bold"><?= number_format($p['price']) ?>đ</span>
                                            <?php endif; ?>
                                        </div>
                                        <button class="btn btn-sm btn-primary" onclick="addToCart(<?= $p['id'] ?>)"><i class="fas fa-cart-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if (!empty($pagination) && ($pagination['totalPages'] ?? 1) > 1): 
                $page = (int)$pagination['currentPage'];
                $total = (int)$pagination['totalPages'];
                $params = $_GET; 
            ?>
                <nav aria-label="Phân trang" class="mt-3">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <?php $params['page'] = max(1, $page - 1); ?>
                            <a class="page-link" href="/products?<?= http_build_query($params) ?>"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <?php for ($i = 1; $i <= $total; $i++): $params['page'] = $i; ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="/products?<?= http_build_query($params) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $total ? 'disabled' : '' ?>">
                            <?php $params['page'] = min($total, $page + 1); ?>
                            <a class="page-link" href="/products?<?= http_build_query($params) ?>"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `product_id=${productId}&quantity=1`
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            document.getElementById('cart-count')?.textContent = d.cartCount;
        }
    })
}
</script>
