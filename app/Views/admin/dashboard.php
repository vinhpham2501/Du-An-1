<?php $title = 'Trang thống kê nhà hàng Restaurant'; ?>

<style>
.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin: -1.5rem -1.5rem 2rem -1.5rem;
    border-radius: 0 0 15px 15px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-card.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card.success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    color: white;
}

.stat-card.info {
    background: linear-gradient(135deg, #3498db 0%, #85c1e9 100%);
    color: white;
}

.stat-card.warning {
    background: linear-gradient(135deg, #f39c12 0%, #f7dc6f 100%);
    color: white;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    margin: 0.5rem 0;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.chart-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.chart-header {
    border-bottom: 2px solid #f8f9fa;
    padding-bottom: 1rem;
    margin-bottom: 1.5rem;
}

.chart-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.table-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.table-modern {
    border: none;
}

.table-modern th {
    border: none;
    background: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
    padding: 1rem;
}

.table-modern td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
}

.table-modern tbody tr {
    border-bottom: 1px solid #f1f3f4;
}

.table-modern tbody tr:hover {
    background: #f8f9fa;
}

.badge-modern {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
}

.product-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #f1f3f4;
    transition: background 0.3s ease;
}

.product-item:hover {
    background: #f8f9fa;
}

.product-item:last-child {
    border-bottom: none;
}

.product-rank {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 1rem;
}

.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-left: auto;
}
</style>

<!-- Dashboard Header -->
<div class="dashboard-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2">Trang thống kê nhà hàng Restaurant</h1>
                <p class="mb-0 opacity-75">Chào mừng bạn quay trở lại! Đây là tổng quan về hoạt động của website.</p>
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
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Tổng số đơn hàng</div>
                    <div class="stat-number"><?= number_format($stats['total_orders']) ?></div>
                    <small>Đơn hàng</small>
                </div>
                <div class="icon-circle" style="background: rgba(255,255,255,0.2);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Doanh thu</div>
                    <div class="stat-number"><?= number_format($stats['total_revenue']) ?>đ</div>
                    <small>VND</small>
                </div>
                <div class="icon-circle" style="background: rgba(255,255,255,0.2);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Sản phẩm</div>
                    <div class="stat-number"><?= number_format($totalProducts) ?></div>
                    <small>Sản phẩm</small>
                </div>
                <div class="icon-circle" style="background: rgba(255,255,255,0.2);">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Thành viên</div>
                    <div class="stat-number"><?= number_format($totalUsers) ?></div>
                    <small>Người dùng</small>
                </div>
                <div class="icon-circle" style="background: rgba(255,255,255,0.2);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Revenue Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="fas fa-chart-line me-2 text-primary"></i>
                    Biểu đồ doanh thu các ngày trong tháng
                </h5>
            </div>
            <div class="chart-area">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Order Status Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="fas fa-chart-pie me-2 text-success"></i>
                    Thống kê trạng thái đơn hàng
                </h5>
            </div>
            <div class="chart-area">
                <canvas id="statusChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row -->
<div class="row">
    <!-- Top Products -->
    <div class="col-xl-6 col-lg-6">
        <div class="table-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="fas fa-star me-2 text-warning"></i>
                    Top sản phẩm bán chạy
                </h5>
            </div>
            
            <?php if (empty($topProducts)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có dữ liệu sản phẩm bán chạy</p>
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
    <div class="col-xl-6 col-lg-6">
        <div class="table-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="fas fa-clock me-2 text-info"></i>
                    Danh sách đơn hàng mới
                </h5>
            </div>
            
            <?php if (empty($recentOrders)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có đơn hàng nào</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-modern">
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
// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            <?php foreach ($dailyRevenue as $data): ?>
                '<?= date('d/m', strtotime($data['date'])) ?>',
            <?php endforeach; ?>
        ],
        datasets: [{
            label: 'Doanh thu',
            data: [
                <?php foreach ($dailyRevenue as $data): ?>
                    <?= $data['revenue'] ?>,
                <?php endforeach; ?>
            ],
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#667eea',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6
        }, {
            label: 'Đơn hàng',
            data: [
                <?php foreach ($dailyRevenue as $data): ?>
                    <?= $data['orders'] ?>,
                <?php endforeach; ?>
            ],
            borderColor: '#1cc88a',
            backgroundColor: 'rgba(28, 200, 138, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#1cc88a',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
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

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Hoàn thành', 'Đang xử lý', 'Đã hủy', 'Chờ xác nhận'],
        datasets: [{
            data: [
                <?php
                // Calculate status counts
                $statusCounts = [
                    'completed' => 0,
                    'processing' => 0,
                    'cancelled' => 0,
                    'pending' => 0
                ];
                foreach ($recentOrders as $order) {
                    if ($order['status'] == 'completed') {
                        $statusCounts['completed']++;
                    } elseif (in_array($order['status'], ['confirmed', 'preparing', 'delivering'])) {
                        $statusCounts['processing']++;
                    } elseif ($order['status'] == 'cancelled') {
                        $statusCounts['cancelled']++;
                    } else {
                        $statusCounts['pending']++;
                    }
                }
                ?>
                <?= $statusCounts['completed'] ?>,
                <?= $statusCounts['processing'] ?>,
                <?= $statusCounts['cancelled'] ?>,
                <?= $statusCounts['pending'] ?>
            ],
            backgroundColor: [
                '#1cc88a',
                '#36b9cc', 
                '#e74a3b',
                '#f6c23e'
            ],
            borderWidth: 0,
            cutout: '60%'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            }
        }
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
