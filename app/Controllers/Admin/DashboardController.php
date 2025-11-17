<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    private $orderModel;
    private $productModel;
    private $userModel;
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->orderModel = new Order();
        $this->productModel = new Product();
        $this->userModel = new User();
        $this->categoryModel = new Category();
    }

    public function index()
    {
        try {
            // Get date range from query params or default to last 30 days
            $dateFrom = $_GET['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
            $dateTo = $_GET['date_to'] ?? date('Y-m-d');
            
            $filters = [
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ];
            
            // Get statistics
            $stats = $this->orderModel->getStatistics($filters);
            $dailyRevenue = $this->orderModel->getDailyRevenue(7);
            $topProducts = $this->productModel->getTopSelling(5);
            
            // Get recent orders
            $recentOrders = $this->orderModel->getAll([
                'limit' => 10,
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ]);
            
            // Get counts
            $totalUsers = $this->userModel->count();
            $totalProducts = $this->productModel->count();
            $totalCategories = $this->categoryModel->count();
            
            return $this->render('admin/dashboard', [
                'stats' => $stats,
                'dailyRevenue' => $dailyRevenue,
                'topProducts' => $topProducts,
                'recentOrders' => $recentOrders,
                'totalUsers' => $totalUsers,
                'totalProducts' => $totalProducts,
                'totalCategories' => $totalCategories,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo
            ]);
        } catch (\Exception $e) {
            // Log error for debugging
            error_log("Dashboard Error: " . $e->getMessage());
            
            // Return a simple dashboard with error handling
            return $this->render('admin/dashboard', [
                'stats' => [
                    'total_orders' => 0,
                    'total_revenue' => 0,
                    'avg_order_value' => 0
                ],
                'dailyRevenue' => [],
                'topProducts' => [],
                'recentOrders' => [],
                'totalUsers' => 0,
                'totalProducts' => 0,
                'totalCategories' => 0,
                'dateFrom' => $dateFrom ?? date('Y-m-d', strtotime('-30 days')),
                'dateTo' => $dateTo ?? date('Y-m-d'),
                'error' => 'Có lỗi xảy ra khi tải dữ liệu dashboard'
            ]);
        }
    }

    public function statistics()
    {
        $period = $_GET['period'] ?? '7';
        $dailyRevenue = $this->orderModel->getDailyRevenue($period);
        
        return $this->json([
            'success' => true,
            'data' => $dailyRevenue
        ]);
    }
}
