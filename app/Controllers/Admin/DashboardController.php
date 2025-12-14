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
            // Lấy khoảng ngày từ query hoặc mặc định 30 ngày gần đây
            $dateFrom = $_GET['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
            $dateTo = $_GET['date_to'] ?? date('Y-m-d');
            
            $filters = [
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ];
            
            // Lấy số liệu thống kê
            $stats = $this->orderModel->getStatistics($filters);
            $dailyRevenue = $this->orderModel->getDailyRevenueByRange($dateFrom, $dateTo);
            $topProducts = $this->productModel->getTopSelling(5);
            
            // Lấy các đơn hàng gần đây
            $recentOrders = $this->orderModel->getAll([
                'limit' => 10,
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ]);
            
            // Lấy danh mục và số lượng
            $categories = $this->categoryModel->getAll();

            // Lấy tổng số
            $totalUsers = $this->userModel->count();
            $totalProducts = $this->productModel->count();
            $totalCategories = $this->categoryModel->count();
            
            return $this->render('admin/dashboard', [
                'stats' => $stats,
                'dailyRevenue' => $dailyRevenue,
                'topProducts' => $topProducts,
                'recentOrders' => $recentOrders,
                'categories' => $categories,
                'totalUsers' => $totalUsers,
                'totalProducts' => $totalProducts,
                'totalCategories' => $totalCategories,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo
            ]);
        } catch (\Exception $e) {
            // Ghi log lỗi để debug
            error_log("Dashboard Error: " . $e->getMessage());
            
            // Trả về dashboard đơn giản kèm xử lý lỗi
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
        $dateFrom = $_GET['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
        $dateTo = $_GET['date_to'] ?? date('Y-m-d');
        $dailyRevenue = $this->orderModel->getDailyRevenueByRange($dateFrom, $dateTo);
        
        return $this->json([
            'success' => true,
            'data' => $dailyRevenue
        ]);
    }
}
