<?php $title = 'Bảng điều khiển - Sắc Việt Admin'; ?>

<style>
.dashboard-header {
    background: linear-gradient(135deg, #8B0000 0%, #8B0000 100%);
    color: #fff;
    padding: 2rem 0;
    margin: -18px -18px 2rem -18px;
    border-radius: 0 0 20px 20px;
}

.dashboard-header h1 {
    font-size: 1.85rem;
    font-weight: 700;
}

.stat-card {
    border-radius: 16px;
    padding: 1.4rem;
    min-height: 160px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 30px rgba(15, 23, 42, 0.15);
}

.stat-card .stat-label {
    letter-spacing: 0.08em;
    font-size: 0.75rem;
}

.stat-card .stat-number {
    font-size: 2.6rem;
    font-weight: 700;
}

.stat-card::after {
    content: '';
    position: absolute;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    right: 20px;
    bottom: -30px;
    pointer-events: none;
}

.stat-card.primary {
    background: linear-gradient(135deg, #1c5aebff 0%, #2a53a7ff 100%);
}

.stat-card.success {
    background: linear-gradient(135deg, #18ac42ff 0%, #66d570ff 100%);
}

.stat-card.info {
    background: linear-gradient(135deg, #e43434ff 0%, #d64e18ff 100%);
}

.stat-card.warning {
    background: linear-gradient(135deg, #1a90ebff 0%, #37d59eff 100%);
}

.stat-card i {
    font-size: 1.5rem;
}

.chart-panel,
.report-panel {
    background: #fff;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 15px 30px rgba(15, 23, 42, 0.08);
}

.dual-chart-row .chart-panel {
    min-height: 360px;
}

.chart-panel h5 {
    font-size: 1rem;
    font-weight: 600;
}

.chart-panel small {
    color: #0e6ec3ff;
}

.summary-chart-area {
    height: 360px;
}

.report-panel .chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.table-modern th {
    background: #f7f8fa;
    color: #173d7eff;
}

.product-item {
    border-bottom: 1px solid #f1f3f5;
}

.table-card {
    background: #fff;
    border-radius: 20px;
    padding: 1.5rem 1.75rem;
    box-shadow: 0 12px 25px rgba(15, 23, 42, 0.08);
}

.chart-title {
    font-size: 1rem;
    font-weight: 600;
}

.summary-chart-area {
    height: 280px;
}

.pie-layout {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.25rem;
    width: 100%;
    height: 100%;
}

.pie-container {
    flex: 0 0 80%;       /* ~70% chiều ngang: biểu đồ + legend trạng thái */
    max-width: 360px;
}

.category-legend {
    flex: 0 0 20%;       /* ~30% chiều ngang: danh mục theo doanh thu */
    list-style: none;
    padding-left: 0;
    margin-bottom: 0;
    font-size: 0.85rem;
    text-align:center;      /* chữ danh mục canh trái trong nửa bên phải */
    margin-left: 0;
}

.category-legend li {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding: 0.25rem 0;
}

.category-legend .dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 0.5rem;
}

.category-legend .label-wrapper {
    display: flex;
    align-items: center;
}

/* Canh giữa tiêu đề nhỏ 'Top danh mục theo doanh thu' phía trên legend */
.report-panel .chart-header {
    text-align: center;
}

.report-panel .chart-header small {
    display: block;
}
</style>

<!-- Dashboard Header -->
<div class="dashboard-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2">Tổng quan hoạt động - Sắc Việt</h1>
                <p class="mb-0 opacity-75">Theo dõi nhanh đơn hàng, doanh thu và sản phẩm nổi bật trên hệ thống Sắc Việt.</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <input type="date" class="form-control" id="date_from" value="<?= $dateFrom ?>" style="max-width: 150px;">
                    <input type="date" class="form-control" id="date_to" value="<?= $dateTo ?>" style="max-width: 150px;">
                    <button class="btn btn-light" onclick="updateDateRange()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4 g-3">
    <?php $insights = [
        ['label' => 'Đơn hàng', 'value' => number_format($stats['total_orders']), 'icon' => 'fa-shopping-cart', 'class' => 'primary', 'detail' => 'Tổng đơn hàng'],
        ['label' => 'Doanh thu', 'value' => number_format($stats['total_revenue']) . 'đ', 'icon' => 'fa-wallet', 'class' => 'success', 'detail' => 'Tổng doanh thu'],
        ['label' => 'Sản phẩm', 'value' => number_format($totalProducts), 'icon' => 'fa-box', 'class' => 'info', 'detail' => 'Sản phẩm hiện có'],
        ['label' => 'Thành viên', 'value' => number_format($totalUsers), 'icon' => 'fa-users', 'class' => 'warning', 'detail' => 'Người dùng đăng ký']
    ]; ?>
    <?php foreach ($insights as $insight): ?>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card <?= $insight['class'] ?>">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-label"><?= $insight['detail'] ?></div>
                        <div class="stat-number"><?= $insight['value'] ?></div>
                        <small><?= $insight['label'] ?></small>
                    </div>
                    <div>
                        <i class="fas <?= $insight['icon'] ?>"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
// Thống kê theo trạng thái đơn hàng cho biểu đồ tròn
$statusLabelsChart = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'preparing' => 'Đang chuẩn bị',
    'delivering' => 'Đang giao',
    'completed' => 'Hoàn thành',
    'cancelled' => 'Đã hủy'
];

// Dữ liệu cho biểu đồ tròn: theo trạng thái đơn hàng
$statusStats = [];
foreach ($recentOrders as $order) {
    $statusKey = $order['status'] ?? 'pending';
    $label = $statusLabelsChart[$statusKey] ?? ucfirst($statusKey);
    if (!isset($statusStats[$label])) {
        $statusStats[$label] = 0;
    }
    $statusStats[$label]++;
}

$statusChartData = array_slice($statusStats, 0, 5, true);

// Danh sách danh mục cho phần "Top danh mục theo doanh thu"
$categoryColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'];
$topCategories = array_slice($categories ?? [], 0, 5);
?>

<!-- Revenue Chart Row -->
<div class="row mb-4 dual-chart-row">
    <div class="col-xl-8 mb-4">
        <div class="chart-panel">
            <div class="chart-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-area me-2 text-primary"></i>
                    Doanh thu theo ngày
                </h5>
                <small>Doanh thu và đơn hàng trong khoảng</small>
            </div>
            <div class="summary-chart-area">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4 mb-4">
        <div class="report-panel">
            <div class="chart-header">
                <h5 class="mb-0">
                    <i class="fas fa-pie-chart me-2 text-warning"></i>
                    Cơ cấu danh mục
                </h5>
                <small>Top danh mục theo doanh thu</small>
            </div>
            <div class="summary-chart-area">
                <div class="pie-layout">
                    <div class="pie-container">
                        <canvas id="assetChart"></canvas>
                    </div>
                    <ul class="category-legend">
                        <?php $i = 0; foreach ($topCategories as $cat): ?>
                            <li>
                                <div class="label-wrapper">
                                    <span class="dot" style="background: <?= $categoryColors[$i % count($categoryColors)] ?>"></span>
                                    <span><?= htmlspecialchars($cat['name']) ?></span>
                                </div>
                            </li>
                        <?php $i++; endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row: Top products & Recent orders -->
<div class="row mb-4">
    <!-- Top Products -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="table-card h-100">
            <div class="chart-header">
                <h5 class="chart-title mb-0">
                    <i class="fas fa-star me-2 text-warning"></i>
                    Top sản phẩm bán chạy
                </h5>
            </div>

            <?php if (empty($topProducts)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Chưa có dữ liệu sản phẩm bán chạy</p>
                </div>
            <?php else: ?>
                <?php foreach ($topProducts as $index => $product): ?>
                    <div class="product-item">
                        <div class="product-rank"><?= $index + 1 ?></div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?= htmlspecialchars($product['name']) ?></h6>
                            <small class="text-muted">Đã bán: <?= $product['total_sold'] ?> sản phẩm</small>
                        </div>
                        <div class="text-end">
                            <div class="text-success fw-bold"><?= number_format($product['revenue']) ?>đ</div>
                            <small class="text-muted">Doanh thu</small>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="table-card h-100">
            <div class="chart-header">
                <h5 class="chart-title mb-0">
                    <i class="fas fa-clock me-2 text-info"></i>
                    Đơn hàng gần nhất
                </h5>
            </div>

            <?php if (empty($recentOrders)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Chưa có đơn hàng nào</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($recentOrders, 0, 5) as $order): ?>
                                <tr>
                                    <td>
                                        <strong class="text-primary">#<?= $order['id'] ?></strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($order['delivery_name'] ?? $order['user_name']) ?></strong>
                                            <br><small class="text-muted"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success"><?= number_format($order['total_amount']) ?>đ</strong>
                                    </td>
                                    <td>
                                        <?php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'confirmed' => 'info',
                                            'preparing' => 'primary',
                                            'delivering' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ xác nhận',
                                            'confirmed' => 'Đã xác nhận',
                                            'preparing' => 'Đang chuẩn bị',
                                            'delivering' => 'Đang giao',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                        $color = $statusColors[$order['status']] ?? 'secondary';
                                        $label = $statusLabels[$order['status']] ?? $order['status'];
                                        ?>
                                        <span class="badge badge-modern bg-<?= $color ?>"><?= $label ?></span>
                                    </td>
                                    <td>
                                        <a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// ==============================
// Biểu đồ doanh thu theo ngày
// - Sử dụng dữ liệu PHP $dailyRevenue (date, revenue, orders)
// - Vẽ 2 dataset: Doanh thu (cột màu tím) và Số đơn hàng (cột màu xanh lá)
// ==============================
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'bar',
    data: {
        // Nhãn trục X: ngày dạng d/m lấy từ trường date
        labels: [
            <?php foreach ($dailyRevenue as $data): ?>
                '<?= date('d/m', strtotime($data['date'])) ?>',
            <?php endforeach; ?>
        ],
        datasets: [{
            // Dataset 1: Tổng doanh thu theo ngày (đơn vị: VND)
            label: 'Doanh thu',
            data: [
                <?php foreach ($dailyRevenue as $data): ?>
                    <?= $data['revenue'] ?>,
                <?php endforeach; ?>
            ],
            backgroundColor: 'rgba(9, 255, 78, 0.8)',
            borderColor: '#76ff7fff',
            borderWidth: 1,
            borderRadius: 6
        }, {
            // Dataset 2: Số lượng đơn hàng theo ngày
            label: 'Đơn hàng',
            data: [
                <?php foreach ($dailyRevenue as $data): ?>
                    <?= $data['orders'] ?>,
                <?php endforeach; ?>
            ],
            backgroundColor: 'rgba(10, 96, 255, 0.85)',
            borderColor: '#7d9dffff',
            borderWidth: 1,
            borderRadius: 6,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                    }
                },
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                beginAtZero: true,
                grid: {
                    drawOnChartArea: false,
                },
                ticks: {
                    callback: function(value) {
                        return value + ' đơn';
                    }
                }
            },
            x: {
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            }
        }
    }
});

const assetCtx = document.getElementById('assetChart').getContext('2d');
const assetChart = new Chart(assetCtx, {
    type: 'doughnut',
    data: {
        // Biểu đồ tròn: theo trạng thái đơn hàng
        labels: <?= json_encode(array_keys($statusChartData)) ?>,
        datasets: [{
            data: <?= json_encode(array_values($statusChartData)) ?>,
            backgroundColor: <?= json_encode($categoryColors) ?>,
            borderWidth: 0,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    boxWidth: 10,
                    padding: 15
                }
            }
        },
        cutout: '70%'
    }
});
function updateDateRange() {
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    
    if (dateFrom && dateTo) {
        window.location.href = `/admin/dashboard?date_from=${dateFrom}&date_to=${dateTo}`;
    }
}
</script>
